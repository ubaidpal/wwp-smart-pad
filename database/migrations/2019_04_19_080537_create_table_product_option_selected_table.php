<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductOptionSelectedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_window_product_option', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->nullable()->default(0)->index();
            $table->integer('product_option_id')->unsigned()->nullable()->default(0)->index();
            $table->string('type')->nullable();
            $table->integer('option_id')->unsigned()->nullable()->index();
            $table->integer('select_id')->unsigned()->nullable()->index();
            $table->string('notes')->nullable();
            $table->float('amount')->nullable();
            $table->float('editable_amount')->nullable();
            $table->integer('brand_id')->unsigned()->nullable()->index();
            $table->integer('item_id')->unsigned()->nullable()->index();
            $table->string('color')->nullable();
            $table->integer('order')->unsigned()->nullable();

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
            $table->softDeletes();
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
        Schema::dropIfExists('table_product_option_selected');
    }
}
