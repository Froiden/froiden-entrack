<?php

namespace App\Console\Commands;

use App\Helper\Reply;
use App\Http\Controllers\ProductController;
use App\Models\EnvatoSetting;
use Illuminate\Console\Command;
use App\Models\SalesFetchSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use stdClass;

class FetchSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch sales from envato';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        try {
            $setting = EnvatoSetting::first();
            Config::set('app.envato_key', $setting->envato_api_key);
        } catch (\Throwable $th) {
            logger($th);
        }

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $settings = SalesFetchSetting::first();
        $settingsFromDate = ($settings->from_date->isPast()) ? $settings->from_date->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $settingsToDate = ($settings->to_date->isPast()) ? $settings->to_date->addDay()->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        if ($settings->total_pages == 0) {
            $this->incrementDates($settingsToDate, $settingsToDate);

        } else if ($settings->total_pages > 0) {
            if ($settings->completed_pages < $settings->total_pages) {
                $pageNo = $settings->completed_pages + 1;

                $url = 'https://api.envato.com/v3/market/user/statement?from_date='.$settingsFromDate.'&to_date='.$settingsToDate.'&page='.$pageNo;
                $response = $this->curl($url);

                foreach ($response['results'] as $key => $value) {
                    $product = Product::where('envato_id', $value['item_id'])->first();
                  
                    if (!is_null($product)) {
                        $productId = $product->id;

                    } else {
                        $createProduct = new ProductController();
                        $request->envato_id = $value['item_id'];
                        $response = $createProduct->getProductDataEnvato($request);

                        if ($response['type'] !== 'error') {
                            $product = Product::create($response['data']);
                            $productId = $product->id;
                        }
                    }

                    if (isset($productId)) {
                        $saleCheck = Sale::where('unique_id', $value['unique_id'])->first();

                        if (is_null($saleCheck)) {
                            Sale::create(
                                [
                                    'product_id' => $productId,
                                    'unique_id' => $value['unique_id'],
                                    'date' => Carbon::parse($value['date'], 'Australia/Melbourne') ->setTimezone('UTC')->toDateTimeString(),
                                    'order_id' => $value['order_id'],
                                    'type' => $value['type'],
                                    'detail' => $value['detail'],
                                    'item_id' => $value['item_id'],
                                    'document' => $value['document'],
                                    'price' => $value['price'],
                                    'au_gst' => $value['au_gst'],
                                    'eu_vat' => $value['eu_vat'],
                                    'us_rwt' => $value['us_rwt'],
                                    'us_bwt' => $value['us_bwt'],
                                    'amount' => $value['amount'],
                                    'site' => $value['site'],
                                    'other_party_country' => $value['other_party_country'],
                                    'other_party_region' => $value['other_party_region'],
                                    'other_party_city' => $value['other_party_city'],
                                    'other_party_zipcode' => $value['other_party_zipcode'],
                                ]
                            );
                        }
    
                    }
                }

                $this->incrementCompletedPages($pageNo);
           
            } else {
                $this->incrementDates($settingsToDate, $settingsToDate);
            }
        }
    }

    protected function curl($url)
    {

        if (empty($url)) { return false;
        }

        $api_key = config('app.envato_key');

        // Send request to envato to verify purchase
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Envato API Wrapper PHP)');

        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: Bearer ' . $api_key;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $data = curl_exec($ch);
        curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = json_decode($data, true);

        return $response; // string or null
    }

    public function incrementDates($fromDate, $toDate)
    {
        $url = 'https://api.envato.com/v3/market/user/statement?from_date='.$fromDate.'&to_date='.$toDate;
        $response = $this->curl($url);

        if ($response['count'] > 0) {
            if (isset($response['pagination'])) {
                $totalPages = $response['pagination']['pages'];
            }
            else {
                $totalPages = 1;
            }
        } else {
            $totalPages = 0;
        }

        DB::table('sales_fetch_settings')
            ->update(
                [
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                    'total_pages' => $totalPages,
                    'completed_pages' => 0
                ]
            );
    }

    public function incrementCompletedPages($pageNo)
    {
        DB::table('sales_fetch_settings')
            ->update(
                [
                   'completed_pages' => $pageNo
                ]
            );
    }

}
