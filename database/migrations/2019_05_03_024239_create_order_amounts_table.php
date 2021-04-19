<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_prices', function (Blueprint $table) {
            $table->increments('id');

            $table->decimal('base_price', 10, 2);
            $table->decimal('retail_price', 10, 2);
            $table->decimal('cost_price', 10, 2);
            $table->decimal('profit', 10, 2);
            $table->string('discount_received')->nullable();
            $table->string('adjustment_value')->nullable();
            $table->string('adjustment')->nullable();

            $table->integer('order_window_product_id')->unsigned()->nullable();
            $table->foreign('order_window_product_id')->references('id')->on('order_window_products')->onDelete('cascade');

            $table->integer('order_window_product_option_id')->unsigned()->nullable();
            $table->foreign('order_window_product_option_id')->references('id')->on('order_window_product_options')->onDelete('cascade');

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            
            $table->integer('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

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
        Schema::dropIfExists('order_prices');
    }
}
