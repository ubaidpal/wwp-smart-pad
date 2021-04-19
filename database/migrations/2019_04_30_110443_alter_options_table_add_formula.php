<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOptionsTableAddFormula extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->Integer('connected_to')->unsigned()->after('constraint_id')->nullable();
            $table->longText('calculation')->after('constraint_id')->nullable();
            $table->boolean('is_quantity')->after('constraint_id')->default(0);
            $table->boolean('is_dollar_value')->after('constraint_id')->default(0);
            $table->boolean('is_fabric_cuts')->after('constraint_id')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropColumn('connected_to');
            $table->dropColumn('calculation');
            $table->dropColumn('is_quantity');
            $table->dropColumn('is_dollar_value');
            $table->dropColumn('is_fabric_cuts');
        });
    }
}
