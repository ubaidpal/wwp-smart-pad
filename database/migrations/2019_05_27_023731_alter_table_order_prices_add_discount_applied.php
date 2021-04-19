<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrderPricesAddDiscountApplied extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('order_prices', function(Blueprint $table) {
           $table->decimal('discount_applied', 10, 2)->nullable();
           $table->dropColumn('profit');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('order_prices', function(Blueprint $table) {
           $table->dropColumn('discount_applied');
           $table->dropColumn('discount_type');
           $table->decimal('profit', 10, 2)->nullable();
       });
    }
}
