<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProductController;
use App\Models\EnvatoSetting;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class FetchProductDetails extends Command
{

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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch-product-details';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch product details from envato';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Request $request)
    {
        $product = Product::where('fetched', 0)->first();

        if (!is_null($product)) {
            $createProduct = new ProductController();
            $request->envato_id = $product->envato_id;
            $response = $createProduct->getProductDataEnvato($request);

            if ($response['type'] === 'error') {
                logger($response['message']);
                return false;
            }

            $response['data']['fetched'] = 1;
    
            $product->update($response['data']);

        } else {
            Product::where('fetched', 1)->update(['fetched' => 0]);
        }
    }

}
