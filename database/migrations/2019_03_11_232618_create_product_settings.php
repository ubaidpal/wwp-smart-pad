<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');

            $table->integer('collection_id')->unsigned()->nullable();
            $table->foreign('collection_id')->references('id')->on('collections');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('cost_adjustment')->integer()->nullable(false);
            $table->integer('price_adjustment')->integer()->nullable(false);
            $table->string('price_adjustment_type')->string(50)->nullable(false);
            $table->integer('eta')->integer()->nullable(false);
            $table->integer('base_price')->integer()->nullable(false);
            $table->integer('cost')->integer()->nullable(false);
            $table->integer('sell_for')->integer()->nullable(false);
            $table->integer('profit')->integer()->nullable(false);
            $table->boolean('is_active')->default(false);

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_settings');
    }
}
