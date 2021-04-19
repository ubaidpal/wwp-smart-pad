<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddressableNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('addressable', function (Blueprint $table) {
            $table->string('full_address')->default('')->nullable()->change();
            $table->string('address')->default('')->nullable()->change();
            $table->string('city')->default('')->nullable()->change();
            $table->integer('state_id')->unsigned()->nullable()->change();
            $table->string('zip')->default('')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('addressable', function (Blueprint $table) {
            $table->string('full_address')->default('')->change();
            $table->string('address')->default('')->change();
            $table->string('city')->default('')->change();
            $table->integer('state_id')->unsigned()->change();
            $table->string('zip')->default('')->change();
        });
    }
}
