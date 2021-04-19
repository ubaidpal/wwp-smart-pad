<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_events', function (Blueprint $table) {
            $table->increments('id');

            $table->string('event');
            $table->string('message_id');

            $table->dateTime('time');
            $table->string('email');
            $table->string('ip');
            $table->string('geo');
            $table->string('custom_id')->nullable(true);
            $table->foreign('custom_id')->references('custom_id')->on('message_loggable');
            $table->string('payload')->nullable(true);

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
        Schema::dropIfExists('mail_events');
    }
}
