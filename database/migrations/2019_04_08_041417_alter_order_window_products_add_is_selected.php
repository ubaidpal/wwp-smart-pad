<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderWindowProductsAddIsSelected extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_window_products', function (Blueprint $table) {
            $table->boolean('is_selected')->default(false)->after('order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_window_products', function (Blueprint $table) {
            $table->dropColumn('is_selected');
        });    
    }
}
