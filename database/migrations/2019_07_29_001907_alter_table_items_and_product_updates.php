<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableItemsAndProductUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('item_updates_read');
        Schema::dropIfExists('product_updates_read');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('item_updates_read', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('item_id')->unsigned()->nullable()->default(0)->index();

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
        });

        Schema::create('product_updates_read', function (Blueprint $table) {
            
            $table->increments('id');

            $table->integer('product_id')->unsigned()->nullable()->default(0)->index();

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
        });
    }
}
