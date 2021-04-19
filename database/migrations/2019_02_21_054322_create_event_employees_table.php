<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_employees', function (Blueprint $table) {
            $table->increments('id'); 

            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->integer('event_id')->unsigned();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

            $table->string('google_calendar_event_id')->default('')->nullable();
            $table->boolean('sync')->default(false);

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
        Schema::dropIfExists('event_employees');
    }
}
