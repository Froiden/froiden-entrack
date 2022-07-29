<?php

namespace App\Http\Controllers;

use App\Models\EnvatoSetting;
use App\Models\Product;
use App\Models\License;
use App\Models\Sale;
use App\Models\UserBadge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalLicenses = License::count();

        $totalSales = Product::sum('number_of_sales');
        $products = Product::all();
        $envatoSetting = EnvatoSetting::first();

        if (is_null($envatoSetting)) {
            return redirect(route('settings.index'));
        }

        $userBadges = UserBadge::all();

        $todayFrom = now(envatoSetting()->timezone)->startOfDay()->setTimezone('UTC')->toDateTimeString();
        $todayTo = now(envatoSetting()->timezone)->endOfDay()->setTimezone('UTC')->toDateTimeString();

        $yesterdayFrom = now(envatoSetting()->timezone)->subDay()->startOfDay()->setTimezone('UTC')->toDateTimeString();
        $yesterdayTo = now(envatoSetting()->timezone)->subDay()->endOfDay()->setTimezone('UTC')->toDateTimeString();

        $monthFrom = now(envatoSetting()->timezone)->startOfMonth()->setTimezone('UTC')->toDateTimeString();
        $monthTo = now(envatoSetting()->timezone)->endOfMonth()->setTimezone('UTC')->toDateTimeString();

        $todaySales = Sale::selectRaw('sales.*, sum(amount) as sums')
            ->whereBetween('date', [$todayFrom, $todayTo])
            ->orderBy('date', 'desc')
            ->with('product')
            ->where(
                function ($query) {
                    return $query->where('type', 'Sale')
                        ->orWhere('type', 'Author Fee');
                }
            )
            ->groupBy('sales.order_id', 'sales.item_id')
            ->get();

        $yesterDaySales = Sale::selectRaw('sales.id')
            ->whereBetween('date', [$yesterdayFrom, $yesterdayTo])
            ->orderBy('date', 'desc')
            ->where(
                function ($query) {
                    return $query->where('type', 'Sale')
                        ->orWhere('type', 'Author Fee');
                }
            )
            ->groupBy('sales.order_id', 'sales.item_id')
            ->get();

        $monthSales = Sale::selectRaw('sales.id')
            ->whereBetween('date', [$monthFrom, $monthTo])
            ->orderBy('date', 'desc')
            ->where(
                function ($query) {
                    return $query->where('type', 'Sale')
                        ->orWhere('type', 'Author Fee');
                }
            )
            ->groupBy('sales.order_id', 'sales.item_id')
            ->get();


        $countryWiseSales = Sale::whereNotNull('other_party_country')
            ->selectRaw('COUNT(DISTINCT(document)) as sales_count, (select iso from countries where countries.nicename = sales.other_party_country) as country_code, other_party_country')
            ->groupBy('country_code')
            ->where('type', 'Sale')
            ->orderBy('sales_count', 'desc')
            ->limit(10)
            ->get();

        return view('home', ['totalProducts' => $totalProducts, 'totalLicenses' => $totalLicenses, 'totalSales' => $totalSales, 'products' => $products, 'envatoSetting' => $envatoSetting, 'userBadges' => $userBadges, 'todaySales' => $todaySales, 'countryWiseSales' => $countryWiseSales, 'yesterDaySales' => $yesterDaySales, 'monthSales' => $monthSales]);
    }

}
