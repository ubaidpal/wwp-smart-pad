<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTaggableUpdateTagId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taggable', function(Blueprint $table) {
            $table->dropColumn('tag');
            $table->integer('tag_id')->unsigned();
            $table->index('tag_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taggable', function(Blueprint $table) {
            $table->string('tag');
            $table->dropIndex('taggable_tag_id_index');
            $table->dropColumn('tag_id');
        });
    }
}
