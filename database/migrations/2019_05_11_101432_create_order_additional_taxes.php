<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAdditionalTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_additional_taxes', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('name');
            $table->string('value');
            $table->string('type');

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
        Schema::dropIfExists('order_additional_taxes');
    }
}
