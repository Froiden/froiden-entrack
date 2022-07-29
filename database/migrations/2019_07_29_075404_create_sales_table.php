<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('set null');

            $table->string('unique_id');
            $table->dateTime('date');
            $table->string('order_id')->nullable();
            $table->string('type');
            $table->text('detail');
            $table->string('item_id')->nullable();
            $table->string('document')->nullable();
            $table->float('price')->nullable();
            $table->float('au_gst')->nullable();
            $table->float('eu_vat')->nullable();
            $table->float('us_rwt')->nullable();
            $table->float('us_bwt')->nullable();
            $table->float('amount');
            $table->string('site')->nullable();
            $table->string('other_party_country')->nullable();
            $table->string('other_party_region')->nullable();
            $table->string('other_party_city')->nullable();
            $table->string('other_party_zipcode')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }

}
