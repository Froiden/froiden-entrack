<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\SalesFetchSetting;

class CreateSalesFetchSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_fetch_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('completed_pages')->default(0);
            $table->integer('total_pages')->default(0);
            $table->boolean('cron_fetch')->default(0);
            $table->timestamps();
        });

        $setting = new SalesFetchSetting();
        $setting->from_date = '2015-02-01';
        $setting->to_date = '2015-02-01';
        $setting->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_fetch_settings');
    }

}
