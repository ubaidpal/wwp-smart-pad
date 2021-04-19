<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('text_color')->nullable();
            $table->string('background_color')->nullable();
            
            $table->string('email')->unique();
            $table->boolean('is_default_recipient')->default(false);

            $table->longText('email_signature')->nullable();
            $table->text('notes')->nullable();
            $table->string('status');
            $table->boolean('is_appointee')->default(false);
            $table->decimal('maximum_discount');
            
            $table->string('google_calendar_id')->nullable();
            $table->text('google_token')->nullable();

            $table->integer('account_id')->nullable()->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->integer('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');
            $table->integer('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');
            
            $table->rememberToken();
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
        Schema::dropIfExists('employees');
    }
}
