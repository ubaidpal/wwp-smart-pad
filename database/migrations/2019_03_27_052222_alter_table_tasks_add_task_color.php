<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTasksAddTaskColor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('task_color_id')->unsigned()->nullable();
            $table->foreign('task_color_id')->references('id')->on('task_colors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('tasks_task_color_id_foreign');
            $table->dropColumn('task_color_id');
        });
    }
}
