<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_employees', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->integer('task_id')->nullable()->unsigned();
            $table->foreign('task_id')->references('id')->on('tasks');

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
        Schema::dropIfExists('task_employees');
    }
}
