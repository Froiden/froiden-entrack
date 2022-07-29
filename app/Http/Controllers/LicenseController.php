<?php

namespace App\Http\Controllers;

use App\Http\Requests\License\FeedbackSubmit;
use Illuminate\Http\Request;
use App\Models\License;
use App\Http\Requests\License\UpdateLicense;
use App\Helper\Reply;
use App\Http\Requests\License\StoreLicense;
use App\Models\Product;
use App\Http\Requests\License\VerifyLicense;
use Carbon\Carbon;

class LicenseController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function index()
    {

        if (is_null(envatoSetting())) {
            return redirect(route('settings.index'));
        }

        $products = Product::all();
        $productFilter = request()->input('product');
        $purchaseCode = request()->input('code');
        $domain = request()->input('domain');
        $username = request()->input('username');
        $version = request()->input('version');
        $supportExpired = request()->input('supported');
        $ratingFilter = request()->input('rating');

        $licenses = License::with('product')->orderBy('id', 'desc');

        if ($productFilter != '') {
            $licenses = $licenses->where('product_id', $productFilter);
        }

        if ($purchaseCode != '') {
            $licenses = $licenses->where('purchase_code', $purchaseCode);
        }

        if ($domain != '') {
            $licenses = $licenses->where('app_url', 'like', '%' . $domain . '%');
        }

        if ($username != '') {
            $licenses = $licenses->where('buyer_username', 'like', '%' . $username . '%');
        }

        if ($supportExpired != '' && $supportExpired == 'no') {
            $now = Carbon::now()->toDateTimeString();
            $licenses = $licenses->where('supported_until', '<', $now);
        }

        if ($supportExpired != '' && $supportExpired == 'yes') {
            $now = Carbon::now()->toDateTimeString();
            $licenses = $licenses->where('supported_until', '>=', $now);
        }

        $licenses = $licenses->paginate(15);

        return view('licenses.index', ['licenses' => $licenses, 'products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(403);
        $products = Product::all();
        return view('licenses.create', ['products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLicense $request)
    {
        $checkProduct = Product::where('envato_id', $request->envato_item_id)->first();

        if (is_null($checkProduct)) {
            return Reply::error('Product does not exist. Add the product first.');
        }

        $license = new License();
        $license->purchase_code = $request->purchase_code;
        $license->purchased_on = $request->purchased_on;
        $license->supported_until = $request->supported_until;
        $license->license_type = $request->license_type;
        $license->buyer_username = $request->buyer_username;
        $license->earning = $request->earning;
        $license->product_id = $checkProduct->id;
        $license->save();
        return Reply::redirect(route('licenses.index'), 'License added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $license = License::with('product')->find($id);
        return view('licenses.edit', ['license' => $license]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLicense $request, $id)
    {
        $license = License::findOrFail($id);
        $license->app_url = $request->app_url;
        $license->purchase_code = $request->purchase_code;
        $license->save();
        return Reply::redirect(route('licenses.index'), 'License updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        License::destroy($id);
        return Reply::success('License deleted successfully.');
    }

    public function verify()
    {
        return view('licenses.verify');
    }

    public function verifyCode(VerifyLicense $request)
    {
        $purchaseCode = $request->purchase_code;

        // Gets author data & prepare verification vars
        $purchase_code = urlencode($purchaseCode);

        $url = 'https://api.envato.com/v3/market' . '/author/sale?code=' . $purchase_code;

        $response = $this->curl($url);
        $response['purchase_code'] = $purchaseCode;

        if (!isset($response['sold_at'])) {
            return Reply::error('Invalid purchase code');
        }

        return Reply::dataOnly([
            'html' => view('licenses.verify_response', ['response' => $response])->render(),
            'status' => 'success'
        ]);
    }

}
