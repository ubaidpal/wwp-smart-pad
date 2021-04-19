<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePermissionsAddAccountIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function(Blueprint $table) {
            $table->integer('account_id')->default(null)->nullable()->after('employee_id');
        });
        Schema::table('employee_roles', function(Blueprint $table) {
            $table->integer('account_id')->default(null)->nullable()->after('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function(Blueprint $table) {
            $table->dropColumn('account_id'); 
        });
        Schema::table('employee_roles', function(Blueprint $table) {
            $table->dropColumn('account_id'); 
        });
    }
}
