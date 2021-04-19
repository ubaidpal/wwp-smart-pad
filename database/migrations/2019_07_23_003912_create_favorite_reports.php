<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_reports', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('favorite_id')->nullable(true)->unsigned();
            $table->foreign('favorite_id')->references('id')->on('favorites');

            $table->string('chart_type')->nullable()->default(null);
            $table->string('report_type')->nullable()->default(null);
            $table->datetime('date_from');
            $table->datetime('date_to');
            $table->string('report_type_object')->nullable()->default(null);
            $table->string('employee_ids')->nullable()->default(null);

            $table->integer('account_id')->nullable(true)->unsigned();
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
        Schema::dropIfExists('favorite_reports');
    }
}
