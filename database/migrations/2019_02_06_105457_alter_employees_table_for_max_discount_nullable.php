<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEmployeesTableForMaxDiscountNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('maximum_discount')->nullable()->change();
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
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('maximum_discount')->change();
        });
    }
}
