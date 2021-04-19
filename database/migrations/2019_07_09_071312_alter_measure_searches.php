<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMeasureSearches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('measure_install_searches', function(Blueprint $table) {
            $table->boolean('is_master_console')->default(0)->nullable();
            $table->integer('order')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('measure_install_searches', function(Blueprint $table) {
            $table->dropColumn('is_master_console');
            $table->dropColumn('order');
        });
    }
}
