<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEventsAddFormType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function(Blueprint $table) {
            $table->string('form_type')->default('appointment');
            $table->integer('company_id')->unsigned()->nullable()->change();
            $table->integer('contact_id')->unsigned()->nullable()->change();
            $table->integer('appointment_status_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function(Blueprint $table) {
            $table->dropColumn('form_type');
            $table->integer('company_id')->unsigned()->change();
            $table->integer('contact_id')->unsigned()->change();
            $table->integer('appointment_status_id')->unsigned()->change();
        });
    }
}
