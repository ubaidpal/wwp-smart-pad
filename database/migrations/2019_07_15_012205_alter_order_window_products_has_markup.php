<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderWindowProductsHasMarkup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE order_window_products ADD discount_markup VARCHAR(8) NOT NULL DEFAULT 'decrease' CHECK (discount_markup in ('increase','decrease'));");
        
        DB::statement("ALTER TABLE order_prices ADD discount_markup VARCHAR(8) NOT NULL DEFAULT 'decrease' CHECK (discount_markup in ('increase','decrease'));");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_window_products', function(Blueprint $table) {
            $table->dropColumn('discount_markup');
        });
        Schema::table('order_prices', function(Blueprint $table) {
            $table->dropColumn('discount_markup');
        });
    }
}
