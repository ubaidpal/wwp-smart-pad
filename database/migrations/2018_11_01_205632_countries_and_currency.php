<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CountriesAndCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique()->nullable();
            $table->string('name');
            $table->string('symbol')->nullable();

            $table->timestamps();
        });


        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('iso2_code')->unique();
            $table->string('iso3_code')->unique();
            $table->string('calling_code');
            $table->enum('measure_system', ['METRIC', 'IMPERIAL'])->default('METRIC');
            $table->string('flag');
            $table->string('native_name');
            $table->unsignedInteger('currency_id');

            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->boolean('active')->default(0);
            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropForeign('countries_currencyId_foreign');
        });
        Schema::drop('countries');
        Schema::drop('currencies');
    }
}
