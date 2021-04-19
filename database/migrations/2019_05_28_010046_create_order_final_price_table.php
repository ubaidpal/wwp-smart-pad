<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderFinalPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_final_prices', function (Blueprint $table) {
            $table->increments('id');

            $table->decimal('installation', 10, 2);
            $table->decimal('shipping_and_handling', 10, 2);
            $table->decimal('discounted_sub_total', 10, 2);
            $table->decimal('sub_total', 10, 2);
            $table->decimal('tax', 10, 2);
            $table->decimal('total', 10, 2);

            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders');
            
            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');
            
            $table->integer('updated_by')->unsigned();
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
        Schema::dropIfExists('order_final_prices');
    }
}
