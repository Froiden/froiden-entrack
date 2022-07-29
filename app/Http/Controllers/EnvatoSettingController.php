<?php

namespace App\Http\Controllers;

use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Setting\StoreSetting;
use App\Models\EnvatoSetting;
use DateTimeZone;
use Illuminate\Support\Facades\Artisan;

class EnvatoSettingController extends Controller
{

    public function index()
    {
        $settings = EnvatoSetting::first();
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return view('settings.index', ['settings' => $settings, 'timezones' => $timezones]);
    }

    public function store(StoreSetting $request)
    {
        $setting = EnvatoSetting::firstOrNew();
        $setting->envato_username = $request->envato_username;
        $setting->envato_api_key = $request->envato_api_key;
        $setting->timezone = $request->timezone;

        if ($request->hasFile('logo')) {
            $setting->logo = Files::upload($request->logo, 'app-logo');
        }

        $setting->daily_email = ($request->has('daily_email') ? 1 : 0);

        $setting->save();

        Artisan::call('fetch-user-details');
        Artisan::call('fetch-user-badges');
        Artisan::call('fetch-sales-month');
        session()->forget('envato_setting');
        return Reply::redirect(route('home'), 'Settings updated successfully');
    }

}
