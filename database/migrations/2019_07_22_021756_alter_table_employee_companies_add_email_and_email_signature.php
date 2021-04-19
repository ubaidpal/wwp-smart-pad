<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEmployeeCompaniesAddEmailAndEmailSignature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_companies', function($table) {
            $table->string('email')->default('')->nullable();
            $table->text('email_signature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_companies', function($table) {
            $table->dropColumn('email');
            $table->dropColumn('email_signature');
        });
    }
}
