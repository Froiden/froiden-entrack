<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_name');
            $table->string('framework')->nullable();
            $table->string('product_link');
            $table->string('product_thumbnail');
            $table->unsignedBigInteger('parent_product_id')->nullable();
            $table->foreign('parent_product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('set null');
            $table->boolean('is_plugin')->default(0);
            $table->text('summary')->nullable();
            $table->unsignedInteger('envato_id')->nullable();
            $table->string('envato_site')->nullable();
            $table->float('rating')->nullable();
            $table->integer('rating_count')->nullable();
            $table->integer('number_of_sales');
            $table->dateTime('last_updated_at')->nullable();
            $table->boolean('fetched');
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
        Schema::dropIfExists('products');
    }

}
