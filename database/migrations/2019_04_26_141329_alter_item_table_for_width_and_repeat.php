<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterItemTableForWidthAndRepeat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('items', function (Blueprint $table) {
            $table->integer('fabric_width')->default('0')->after('name');
            $table->integer('fabric_repeat')->default('0')->after('name');
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
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('fabric_width');
            $table->dropColumn('fabric_repeat');
        });
    }
}
