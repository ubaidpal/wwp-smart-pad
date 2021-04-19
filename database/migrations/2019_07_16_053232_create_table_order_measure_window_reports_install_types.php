<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrderMeasureWindowReportsInstallTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_measure_window_reports_install_types', function (Blueprint $table) {
           $table->increments('id');

            $table->integer('measure_id')->unsigned();
            $table->foreign('measure_id')->references('id')->on('order_measure_window_reports')->onDelete('cascade');

            $table->integer('install_id')->unsigned();
            $table->foreign('install_id')->references('id')->on('install_types')->onDelete('cascade');

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
        Schema::dropIfExists('order_measure_window_reports_install_types');
    }
}
