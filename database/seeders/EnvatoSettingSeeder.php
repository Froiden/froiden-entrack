<?php

namespace Database\Seeders;

use App\Models\EnvatoSetting;
use Illuminate\Database\Seeder;

class EnvatoSettingSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = EnvatoSetting::firstOrNew();
        $setting->envato_username = 'ajay138';
        $setting->sales = '7578';
        $setting->location = 'Jaipur';
        $setting->image = 'https://s3.envato.com/files/225465748/ajay%20copy.jpg';
        $setting->followers = '502';
        $setting->envato_api_key = 'xxxxxxxxxxxxxxxxxxxxxxx';
        $setting->firstname = 'Ajay';
        $setting->surname = 'Kumar Choudhary';
        $setting->country = 'India';
        $setting->balance = '21346.93';
        $setting->timezone = 'Asia/Kolkata';
        $setting->hide_cron_message = 1;
        $setting->save();
    }

}
