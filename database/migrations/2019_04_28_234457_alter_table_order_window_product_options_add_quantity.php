<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrderWindowProductOptionsAddQuantity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_window_product_options', function(Blueprint $table) {
             $table->float('quantity')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_window_product_options', function(Blueprint $table) {
            $table->dropColumn('quantity'); 
        });
    }
}
