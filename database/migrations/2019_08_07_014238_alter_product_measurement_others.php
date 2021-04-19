<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductMeasurementOthers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_measurement_others', function($table)
        {
            $table->integer('in_reveal_height')->nullable()->change();
            $table->integer('in_reveal_width')->nullable()->change();
            $table->integer('outside_reveal_height')->nullable()->change();
            $table->integer('outside_reveal_width')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_measurement_others', function($table)
        {
            $table->integer('in_reveal_height')->nullable(false)->change();
            $table->integer('in_reveal_width')->nullable(false)->change();
            $table->integer('outside_reveal_height')->nullable(false)->change();
            $table->integer('outside_reveal_width')->nullable(false)->change();
        });
    }
}
