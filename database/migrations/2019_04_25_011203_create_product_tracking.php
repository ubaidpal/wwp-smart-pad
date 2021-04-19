<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable()->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->integer('product_id')->nullable()->unsigned();
            $table->foreign('product_id')->references('id')->on('products');

            $table->boolean('is_received')->default(false);
            $table->date('eta')->nullable(true);

            $table->integer('account_id')->nullable()->unsigned();
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
        Schema::dropIfExists('product_tracking');
    }
}
