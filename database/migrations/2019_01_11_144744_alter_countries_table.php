<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('countries', function (Blueprint $table) {
            $table->string('currency_symbol')->nullable();
            $table->string('date_format')->nullable();
            $table->integer('tax')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('currency_symbol');
            $table->dropColumn('date_format');
            $table->dropColumn('tax');
        });
    }
}
