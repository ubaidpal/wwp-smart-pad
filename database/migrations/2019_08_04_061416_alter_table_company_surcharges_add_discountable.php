<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCompanySurchargesAddDiscountable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_surcharges', function(Blueprint $table) {
            $table->boolean('discountable')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_surcharges', function(Blueprint $table) {
            $table->dropColumn('discountable');
        });
    }
}
