<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMeasureInstallSearches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measure_install_searches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('filters');
            $table->enum('visibility', ['Only Me', 'All', 'Team']);

            $table->integer('team_id')->nullable()->unsigned();
            $table->foreign('team_id')->references('id')->on('teams');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->softDeletes();
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
        Schema::dropIfExists('measure_install_searches');
    }
}
