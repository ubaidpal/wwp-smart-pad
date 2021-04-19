<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderWindowsAudio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audio_recordable', function (Blueprint $table) {
            $table->increments('id');

            $table->string('type')->nullable(true);
            $table->string('audio_recordable_type');
            $table->index('audio_recordable_type');

            $table->string('audio_recordable_id');
            $table->longText('audio');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
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
        Schema::dropIfExists('audio_recordable');
    }
}
