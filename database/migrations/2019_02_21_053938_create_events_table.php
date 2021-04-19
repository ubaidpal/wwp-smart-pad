<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');

            $table->datetime('start')->nullable();
            $table->datetime('end')->nullable();
            $table->text('title');

            $table->integer('company_id')->nullable()->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->integer('contact_id')->nullable()->unsigned();
            $table->foreign('contact_id')->references('id')->on('contacts');

            $table->string('type');
            
            $table->text('appointment_status_email_token')->nullable();
            $table->text('appointment_status_sms_token')->nullable();
            $table->integer('appointment_status_id')->nullable()->unsigned();
            $table->foreign('appointment_status_id')->references('id')->on('appointment_statuses');
            $table->text('appointment_status_notes')->nullable();
            
            $table->text('notes')->nullable();
            
            $table->integer('referred_by_id')->nullable()->unsigned();
            $table->foreign('referred_by_id')->references('id')->on('referred_by');

            $table->string('first_name')->default('')->nullable();
            $table->string('last_name')->default('')->nullable();
            $table->string('email')->default('')->nullable();
            $table->string('po_number')->default('')->nullable();

            $table->string('repeat')->default('')->nullable();
            $table->datetime('repeat_end')->nullable();
            
            $table->integer('parent_id')->nullable()->unsigned();
            $table->foreign('parent_id')->references('id')->on('events');
            
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
        Schema::dropIfExists('events');
    }
}
