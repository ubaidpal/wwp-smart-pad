<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDashboardUpdates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboard_updates', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('update_id')->unsigned()->nullable()->default(0)->index();
            $table->string('type')->nullable()->default(null);

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

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
        Schema::dropIfExists('dashboard_updates');
    }
}
