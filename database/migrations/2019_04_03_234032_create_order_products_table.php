<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_window_products', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('brand_id')->unsigned()->default(null)->nullable();
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->integer('category_id')->unsigned()->default(null)->nullable();
            $table->foreign('category_id')->references('id')->on('categories');

            $table->integer('product_id')->unsigned()->default(null)->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->integer('order_window_id')->unsigned();
            $table->foreign('order_window_id')->references('id')->on('order_windows');

            $table->boolean('is_custom')->default(false);

            $table->string('details')->nullable();

            $table->integer('cost_price')->nullable();
            $table->integer('sell_price')->nullable();

            $table->integer('order')->unsigned()->nullable();

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_products');
    }
}
