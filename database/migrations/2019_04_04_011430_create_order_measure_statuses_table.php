<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderMeasureStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_measure_statuses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('status')->index();
            $table->date('date')->nullable();
            $table->text('notes')->nullable();

            $table->integer('employee_id')->unsigned()->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');

            $table->integer('event_id')->unsigned()->nullable();
            $table->foreign('event_id')->references('id')->on('events');

            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('orders');
            
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
        Schema::dropIfExists('order_measure_statuses');
    }
}
