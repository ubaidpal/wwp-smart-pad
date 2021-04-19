<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrderPricesAddSurcharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_window_product_final_prices', function(Blueprint $table) {
            $table->increments('id');

            $table->decimal('base_price', 10, 2);
            $table->decimal('cost_price', 10, 2);
            $table->decimal('retail_price', 10, 2);
            $table->decimal('discounted_retail_price', 10, 2);

            $table->decimal('surcharges', 10, 2);
            $table->decimal('discounted_surcharges', 10, 2);
            $table->decimal('discountable_surcharges', 10, 2);
            $table->longText('surcharges_source')->nullable();

            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->integer('order_window_id')->unsigned()->nullable();
            $table->foreign('order_window_id')->references('id')->on('order_windows');

            $table->integer('order_window_product_id')->unsigned()->nullable();
            $table->foreign('order_window_product_id', 'owpfp_order_window_product_id_foreign')->references('id')->on('order_window_products');
            
            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');
            
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
        });

        Schema::table('order_final_prices', function(Blueprint $table) {
            $table->decimal('surcharges', 10, 2);
            $table->longText('surcharges_source')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_window_product_final_prices');

        Schema::table('order_final_prices', function(Blueprint $table) {
            $table->dropColumn('surcharges');
            $table->dropColumn('surcharges_source');
        });
    }
}
