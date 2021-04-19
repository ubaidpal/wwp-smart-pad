<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductOptionsTableRemoveConstraintId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropColumn('constraint_id');
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
        Schema::table('product_options', function (Blueprint $table) {
            $table->integer('constraint_id')->default(0);
        });
    }
}
