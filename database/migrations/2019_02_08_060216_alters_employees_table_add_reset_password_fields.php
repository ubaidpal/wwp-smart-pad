<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltersEmployeesTableAddResetPasswordFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function(Blueprint $table) {
            $table->string('reset_token')->nullable()->default(NULL)->unique();
            $table->datetime('reset_token_expiration')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function(Blueprint $table) {
            $table->dropColumn('reset_token');
            $table->dropColumn('reset_token_expiration');
        });
    }
}
