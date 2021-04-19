<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderInstallProductReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_install_product_reports', function (Blueprint $table) {
            $table->increments('id');

            $table->text('notes');
            $table->string('status');

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->integer('order_window_id')->unsigned();
            $table->foreign('order_window_id')->references('id')->on('order_windows');

            $table->integer('order_window_product_id')->unsigned();
            $table->foreign('order_window_product_id')->references('id')->on('order_window_products');

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
        Schema::dropIfExists('order_install_product_reports');
    }
}
