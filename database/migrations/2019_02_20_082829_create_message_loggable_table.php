<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageLoggableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_loggable', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();
            $table->string('group')->nullable()->default('');
            $table->integer('message_loggable_id')->unsigned();
            $table->string('message_loggable_type');
            $table->string('name')->nullable()->default('');
            $table->string('subject')->nullable()->default('');
            $table->longText('message');
            $table->longText('attachments')->nullable();
            $table->string('email_sms');
            $table->string('cc')->nullable()->default('');
            $table->string('bcc')->nullable()->default('');
            $table->integer('contact_id')->unsigned()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->string('type');
            $table->longText('api_fields');
            $table->longText('response')->nullable();
            $table->dateTime('response_date')->nullable();

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
        Schema::dropIfExists('message_loggable');
    }
}
