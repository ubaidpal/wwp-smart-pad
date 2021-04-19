<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAccountSmsCredits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_sms_credits', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->decimal('sms_credits', 10, 2)->nullable()->default(0);
            $table->decimal('sms_credits_recurring', 10, 2)->nullable()->default(0);
            $table->date('sms_credits_recurring_start')->nullable();
            $table->dateTime('deleted_at')->nullable();

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
        Schema::dropIfExists('account_sms_credits');
    }
}
