<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_receipts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
        });

        Schema::create('order_receipt_payments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('order_receipt_id')->unsigned();
            $table->foreign('order_receipt_id')->references('id')->on('order_receipts');

            $table->integer('order_payment_id')->unsigned();
            $table->foreign('order_payment_id')->references('id')->on('order_payments');

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
        Schema::dropIfExists('order_receipts');
        Schema::dropIfExists('order_receipt_payments');
    }
}
