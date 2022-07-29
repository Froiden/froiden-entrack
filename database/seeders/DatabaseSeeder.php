<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        config(['app.seeding' => true]);

        Artisan::call('key:generate');
        
        if (!App::environment('codecanyon')) {
            $this->call(UsersTableSeeder::class);
        }
        
        if (App::environment('demo')) {
            $this->call(ProductSeeder::class);
            $this->call(EnvatoSettingSeeder::class);
            $this->call(UserBadgeSeeder::class);
            $this->call(SalesSeeder::class);
            $this->call(LicenseSeeder::class);
        }
    }

}
