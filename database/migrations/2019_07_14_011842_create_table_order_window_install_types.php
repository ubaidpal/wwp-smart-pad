<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrderWindowInstallTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_window_install_types', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('order_window_id')->unsigned();
            $table->foreign('order_window_id')->references('id')->on('order_windows')->onDelete('cascade');

            $table->integer('install_type_id')->unsigned();
            $table->foreign('install_type_id')->references('id')->on('install_types')->onDelete('cascade');

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
        Schema::dropIfExists('order_window_install_types');
    }
}
