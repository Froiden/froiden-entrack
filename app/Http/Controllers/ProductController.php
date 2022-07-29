<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProduct;
use App\Models\Product;
use App\Helper\Reply;
use App\Http\Requests\Product\UpdateProduct;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public function index()
    {
        if (is_null(envatoSetting())) {
            return redirect(route('settings.index'));
        }

        $products = Product::where('status', 1)->get();

        if (auth()->user()) {
            $products = Product::all();
        }

        return view('product.index', ['products' => $products]);
    }

    public function create()
    {
        $products = Product::all();
        return view('product.create', ['products' => $products]);
    }

    public function plugins($productEnvatoID)
    {
        $parent = Product::where('envato_id', $productEnvatoID)->first();

        if (!$parent) {
            return response()->json(['type' => 'fail']);
        }

        $plugin = Product::select('product_name', 'product_link', 'product_thumbnail', 'summary', 'envato_id')
            ->selectRaw('max(versions.version) as version')
            ->leftJoin('versions', 'versions.product_id', '=', 'products.id')
            ->where('parent_product_id', $parent->id)
            ->groupby('products.envato_id')
            ->orderby('versions.id', 'desc')
            ->where('is_plugin', 1)
            ->get();

        return response()->json($plugin);
    }

    public function store(StoreProduct $request)
    {
        $response = $this->getProductDataEnvato($request);

        if ($response['type'] === 'error') {
            return Reply::error($response['message']);
        }

        Product::create($response['data']);

        return Reply::redirect(route('products.index'), 'Product added successfully.');
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return Reply::success('Product deleted successfully.');
    }

    public function edit(Product $product)
    {
        $products = Product::all();
        return view('product.edit', ['product' => $product, 'products' => $products]);
    }

    public function update(UpdateProduct $request, $id)
    {
        $product = Product::findOrFail($id);
        $response = $this->getProductDataEnvato($request);

        if ($response['type'] === 'error') {
            return Reply::error($response['message']);
        }

        unset($response['data']['product_link']);
        $product->update($response['data']);

        return Reply::redirect(route('products.index'), 'Product updated successfully.');
    }

    public function getProductDataEnvato($request)
    {
        $data = $request->all();
        $url = 'https://api.envato.com/v3/market/catalog/item?id=' . $request->envato_id;

        $this->curl($url);
        $response = $this->curl($url);

        if (isset($response['attributes'])) {
            $someArray = $response['attributes'];
            $framework = null;

            foreach ($someArray as $item) {
                if ($item['name'] == 'software-framework') {
                    $framework = (is_array($item['value'])) ? $item['value'][0] : $item['value'];
                    break;
                }
            }
        }

        // Check if item exists or not
        if (isset($response['error'])) {
            return [
                'message' => (isset($response['description'])) ? $response['description'] : $response['error'],
                'type' => 'error'
            ];
        }


        $data['product_name'] = $response['name'];
        $data['framework'] = $framework;
        $data['product_link'] = $response['url'];
        $data['product_thumbnail'] = $response['previews']['icon_preview']['icon_url'];
        $data['envato_id'] = $response['id'];
        $data['envato_site'] = $response['site'];
        $data['rating'] = $response['rating'];
        $data['rating_count'] = $response['rating_count'];
        $data['last_updated_at'] = $response['updated_at'];
        $data['number_of_sales'] = $response['number_of_sales'];

        return [
            'data' => $data,
            'type' => 'success'
        ];
    }

}
