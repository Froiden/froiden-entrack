<?php

namespace App\Console\Commands;

use App\Models\EnvatoSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class FetchUserAccountDetails extends Command
{

    private $setting;

    public function __construct()
    {
        parent::__construct();
        try {
            $this->setting = $setting = EnvatoSetting::first();
            Config::set('app.envato_key', $setting->envato_api_key);
        } catch (\Throwable $th) {
            logger($th);
        }
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-user-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch envato details of user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = 'https://api.envato.com/v1/market/user:'.$this->setting->envato_username.'.json';
        $response = $this->curl($url);

        $url = 'https://api.envato.com/v1/market/private/user/account.json';
        $response2 = $this->curl($url);

        
        EnvatoSetting::where('envato_username', $this->setting->envato_username)->update([
            'sales' => $response['user']['sales'],
            'location' => $response['user']['location'],
            'image' => $response['user']['image'],
            'followers' => $response['user']['followers'],
            'firstname' => $response2['account']['firstname'],
            'surname' => $response2['account']['surname'],
            'country' => $response2['account']['country'],
            'balance' => $response2['account']['balance'],
        ]);


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
