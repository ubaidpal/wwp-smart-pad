<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTiersAddParentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tiers', function(Blueprint $table) {
            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('tiers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tiers', function(Blueprint $table) {
            $table->dropForeign('tiers_parent_id_foreign');
            $table->dropColumn('parent_id');
        });
    }
}
