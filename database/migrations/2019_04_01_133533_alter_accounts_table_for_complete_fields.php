<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAccountsTableForCompleteFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('master_slave')->nullable();
            $table->string('account_type')->nullable();
            $table->string('payment_plan')->nullable();
            $table->string('address')->nullable();
            $table->string('company_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('company_name');
            $table->dropColumn('company_phone');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('postal_code');
            $table->dropColumn('username');
            $table->dropColumn('password');
            $table->dropColumn('master_slave');
            $table->dropColumn('account_type');
            $table->dropColumn('payment_plan');
            $table->dropColumn('address');
            $table->dropColumn('company_address');
        });
    }
}
