<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFoldersTableForParentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('folders', function (Blueprint $table) {
            $table->integer('parent_id')->default('0');;
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
        Schema::table('folders', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
}
