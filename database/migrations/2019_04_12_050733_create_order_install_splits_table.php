<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderInstallSplitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_install_splits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('status')->nullable();
            $table->text('notes')->nullable();
            $table->string('report_status')->nullable();

            $table->date('date')->nullable();

            $table->integer('event_id')->unsigned()->nullable();
            $table->foreign('event_id')->references('id')->on('events');

            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders');
            
            $table->integer('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');
            
            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');
            
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');
            
            $table->timestamps();
        });

        Schema::create('order_install_split_products', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('order_install_split_id')->unsigned();
            $table->foreign('order_install_split_id')->references('id')->on('order_install_splits')->onDelete('cascade');

            $table->integer('order_window_product_id')->unsigned();
            $table->foreign('order_window_product_id')->references('id')->on('order_window_products');
            
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
        Schema::dropIfExists('order_install_split_products');
        Schema::dropIfExists('order_install_splits');
    }
}
