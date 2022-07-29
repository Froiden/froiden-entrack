<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use App\Charts\EarningChart;
use App\Helper\Reply;
use Carbon\Carbon;

class SaleController extends Controller
{

    public function index()
    {
        if (is_null(envatoSetting())) {
            return redirect(route('settings.index'));
        }

        $productFilter = request()->input('product');
        $dateFilter = request()->input('date');
        $fromDateFilter = request()->input('from_date');
        $toDateFilter = request()->input('to_date');

        $products = Product::withCount(
            [
                'sales' => function ($q) use ($fromDateFilter, $toDateFilter) {
                    if ($fromDateFilter != '') {
                        $q->whereDate('sales.date', '>=', $fromDateFilter);
                    }

                    if ($toDateFilter != '') {
                        $q->whereDate('sales.date', '<=', $toDateFilter);
                    }

                    $q->select(DB::raw('COUNT(DISTINCT(document))'));
                }
            ]
        )
            ->withCount(
                [
                    'refunds' => function ($q) use ($fromDateFilter, $toDateFilter) {
                        if ($fromDateFilter != '') {
                            $q->whereDate('sales.date', '>=', $fromDateFilter);
                        }

                        if ($toDateFilter != '') {
                            $q->whereDate('sales.date', '<=', $toDateFilter);
                        }

                        $q->select(DB::raw('COUNT(DISTINCT(document))'));
                    }
                ]
            )
            ->get();

        $totalSales = Sale::where(
            function ($query) {
                return $query->where('type', 'Sale')
                    ->orWhere('type', 'Author Fee')
                    ->orWhere('type', 'Sale Reversal')
                    ->orWhere('type', 'Author Fee Reversal')
                    ->orWhere('type', 'Sale Refund')
                    ->orWhere('type', 'Author Fee Refund');
            }
        );

        if ($fromDateFilter != '') {
            $totalSales = $totalSales->whereDate('date', '>=', $fromDateFilter);
        }

        if ($toDateFilter != '') {
            $totalSales = $totalSales->whereDate('date', '<=', $toDateFilter);
        }

        if ($productFilter != '') {
            if ($productFilter == 'other') {
                $totalSales = $totalSales->whereNull('product_id');

            } else {
                $totalSales = $totalSales->where('product_id', $productFilter);
            }
        }

        $totalEarning = $totalSales->sum('amount');
        $totalSalesCount = $totalSales->where('type', 'Sale')
            ->select('order_id')
            ->groupBy('order_id', 'item_id')
            ->get();
        $totalSalesCount = count($totalSalesCount);

        $totalRefund = Sale::where(
            function ($query) {
                return $query->where('sales.type', 'Sale Reversal')
                    ->orWhere('sales.type', 'Sale Refund');
            }
        );

        if ($productFilter != '') {
            if ($productFilter == 'other') {
                $totalRefund = $totalRefund->whereNull('product_id');

            } else {
                $totalRefund = $totalRefund->where('product_id', $productFilter);
            }
        }

        if ($fromDateFilter != '') {
            $totalRefund = $totalRefund->whereDate('date', '>=', $fromDateFilter);
        }

        if ($toDateFilter != '') {
            $totalRefund = $totalRefund->whereDate('date', '<=', $toDateFilter);
        }

        $totalRefund = $totalRefund->select('document')->groupBy('order_id')
            ->groupBy('item_id')->get();
        $totalRefund = count($totalRefund);

        $others = Sale::whereNull('product_id')->where(
            function ($query) {
                return $query->where('type', 'Sale')
                    ->orWhere('type', 'Author Fee')
                    ->orWhere('type', 'Sale Reversal')
                    ->orWhere('type', 'Author Fee Reversal')
                    ->orWhere('type', 'Sale Refund')
                    ->orWhere('type', 'Author Fee Refund');
            }
        );

        if ($fromDateFilter != '') {
            $others = $others->whereDate('date', '>=', $fromDateFilter);
        }

        if ($toDateFilter != '') {
            $others = $others->whereDate('date', '<=', $toDateFilter);
        }

        $otherEarning = $others->sum('amount');
        $otherCount = $others->where('type', 'Sale')
            ->select('order_id')
            ->groupBy('order_id')
            ->groupBy('item_id')
            ->get();
        $otherCount = count($otherCount);

        if ($dateFilter != '') {
            $sales = Sale::selectRaw('sales.*, sum(amount) as sums')
                ->with('product')
                ->groupBy('sales.order_id', 'sales.item_id')
                ->orderBy('date', $dateFilter);

        } else {
            $sales = Sale::selectRaw('sales.*, sum(amount) as sums')
                ->with('product')
                ->groupBy('sales.order_id', 'sales.item_id')
                ->orderBy('date', 'desc');
        }

        if ($productFilter != '') {
            if ($productFilter == 'other') {
                $sales = $sales->whereNull('product_id');

            } else {
                $sales = $sales->where('product_id', $productFilter);
            }
        }

        if ($fromDateFilter != '') {
            $sales = $sales->whereDate('date', '>=', $fromDateFilter);
        }

        if ($toDateFilter != '') {
            $sales = $sales->whereDate('date', '<=', $toDateFilter);
        }

        $sales = $sales->where(
            function ($query) {
                return $query->where('type', 'Sale')
                    ->orWhere('type', 'Author Fee');
            }
        );

        $sales = $sales->whereNotNull('other_party_country');
        $sales = $sales->paginate(15);

        // return $products;

        return view(
            'sales.index',
            [
                'sales' => $sales,
                'products' => $products,
                'totalEarning' => round($totalEarning, 0),
                'totalSales' => ($totalSalesCount - $totalRefund),
                'otherCount' => $otherCount,
                'otherSum' => round($otherEarning, 2)
            ]
        );
    }

    public function earningChart()
    {
        if (is_null(envatoSetting())) {
            return redirect(route('settings.index'));
        }

        $productFilter = request()->input('product');
        $fromDate = request()->input('from_date');
        $toDate = request()->input('to_date');

        if ($fromDate == '' || $toDate == '') {
            $fromDate = Carbon::now()->subMonth(11)->format('Y-m-01');
            $toDate = Carbon::today()->format('Y-m-d');
        }

        $data = Sale::where(DB::raw('DATE(`date`)'), '>=', $fromDate)
            ->where(DB::raw('DATE(`date`)'), '<=', $toDate)
            ->where(
                function ($query) {
                    return $query->where('type', 'Sale')
                        ->orWhere('type', 'Author Fee')
                        ->orWhere('type', 'Sale Reversal')
                        ->orWhere('type', 'Author Fee Reversal')
                        ->orWhere('type', 'Sale Refund')
                        ->orWhere('type', 'Author Fee Refund');
                }
            );

        if ($productFilter != '') {
            if ($productFilter == 'other') {
                $data = $data->whereNull('product_id');

            } else {
                $data = $data->where('product_id', $productFilter);
            }
        }

        $data = $data->select(
            DB::raw('ROUND(sum(amount), 2) as sums'),
            DB::raw("DATE_FORMAT(date,'%M %Y') as months")
        )
            ->groupBy('months')
            ->orderBy('date', 'desc')
            ->get();

        $chart = new EarningChart;
        $chart->labels($data->keyBy('months')->keys());
        $chart->label('Earnings in USD');
        $chart->dataset('USD', 'bar', $data->pluck('sums')->all());

        $products = Product::all();

        $keys = $data->keyBy('months')->keys();
        $sums = $data->pluck('sums')->all();
        $b = array_keys($sums, max($sums));

        return view(
            'sales.earning_chart',
            [
                'chart' => $chart,
                'products' => $products,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'highestEarningMonth' => $keys[$b[0]],
                'keys' => $keys,
                'sums' => $sums,
                'highestEarningAmount' => max($sums),
            ]
        );
    }

    public function leanBack()
    {
        $products = Product::all();

        if (request()->ajax()) {
            $showProducts = request()->products;
            $view = view('sales.leanback_ajax', ['products' => $products, 'showProducts' => $showProducts])->render();
            return Reply::dataOnly(['html' => $view]);
        }

        return view('sales.leanback', ['products' => $products]);
    }

}
