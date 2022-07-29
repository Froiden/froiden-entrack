<?php

namespace App\Console\Commands;

use App\Models\EnvatoSetting;
use App\Models\SalesFetchSetting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class FetchSalesByMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-sales-month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch sales by month';

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
     * @return int
     */
    public function handle()
    {
        $settings = SalesFetchSetting::first();

        if ($settings->cron_fetch == 0) {
            $url = 'https://api.envato.com/v1/market/private/user/earnings-and-sales-by-month.json';
            $response = $this->curl($url);
            
            $salesFromDate = Carbon::parse($response['earnings-and-sales-by-month'][0]['month'])->startOfMonth();
            
            $salesToDate = Carbon::parse($response['earnings-and-sales-by-month'][0]['month'])->startOfMonth()->addDay();
            
            $defaultFrom = Carbon::parse('2015-02-01');

            if ($salesFromDate->gt($defaultFrom))
            {
                $settings->from_date = $salesFromDate->toDateTimeString();
                $settings->to_date = $salesToDate->toDateTimeString();
            }

            $settings->cron_fetch = 1;
            $settings->save();
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

}
