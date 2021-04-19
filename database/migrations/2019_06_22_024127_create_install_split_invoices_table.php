<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallSplitInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_install_split_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('minimum_deposit', 10, 2);
            $table->decimal('payments_made', 10, 2);
            $table->decimal('balance_due', 10, 2);
            $table->decimal('sub_total', 10, 2);
            $table->longText('taxes');
            $table->decimal('total', 10, 2);
            $table->integer('install_split_id')->unsigned();
            $table->foreign('install_split_id')->references('id')->on('order_install_splits');
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_install_split_invoices');
    }
}
