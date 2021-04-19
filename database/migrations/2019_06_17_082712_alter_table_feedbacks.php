<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableFeedbacks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('feedbacks', function ($table) {
            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('feedbacks', function ($table) {
            $table->dropColumn('updated_by');
        });
    }
}
