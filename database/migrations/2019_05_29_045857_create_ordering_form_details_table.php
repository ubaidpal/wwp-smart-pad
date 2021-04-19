<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderingFormDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordering_form_details', function (Blueprint $table) {
            $table->increments('id');

            $table->string('purchase_order_id')->unique();
            $table->text('notes')->nullable();

            $table->integer('delivery_address_id')->nullable()->unsigned();
            $table->foreign('delivery_address_id')->references('id')->on('delivery_addresses');

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->integer('brand_id')->unsigned();
            $table->foreign('brand_id')->references('id')->on('brands');

            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');

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
        Schema::dropIfExists('ordering_form_details');
    }
}
