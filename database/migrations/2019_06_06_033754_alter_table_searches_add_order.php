<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSearchesAddOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workflow_searches', function(Blueprint $table) {
            $table->integer('order')->default(0);
        });

        Schema::table('quote_searches', function(Blueprint $table) {
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workflow_searches', function(Blueprint $table) {
            $table->dropColumn('order');
        });
        
        Schema::table('quote_searches', function(Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
