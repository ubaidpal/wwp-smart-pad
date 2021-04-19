<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
            $table->string('background_color');
            $table->string('text_color');

            $table->integer('account_id')->nullable()->default(null)->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->boolean('required')->default(0);
            $table->boolean('is_active')->default(1);

            $table->integer('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_statuses');
    }
}
