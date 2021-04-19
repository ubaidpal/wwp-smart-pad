<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderInstallSplitProductsIsReceived extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_install_split_products', function(Blueprint $table) {
            $table->boolean('is_received')->default(0)->nullable();

            $table->integer('account_id')->nullable(true)->unsigned()->after('is_received');
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('updated_by')->nullable(true)->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return voidcd git
     */
    public function down()
    {
        Schema::table('order_install_split_products', function(Blueprint $table) {
            $table->dropColumn('is_received');
            $table->dropColumn('account_id');
            $table->dropColumn('updated_by');
        });
    }
}
