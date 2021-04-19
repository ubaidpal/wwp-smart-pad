<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableQuoteDocumentsChangeWindowIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_documents', function (Blueprint $table) {
            $table->dropForeign(['window_id']); // drop foreign key
            $table->integer('window_id')->nullable(true)->unsigned()->change(); // change the column definition to nullable
            $table->foreign('window_id')->references('id')->on('windows'); // reassign the references by creating the index
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_documents', function (Blueprint $table) {
            $table->integer('window_id')->nullable(false)->unsigned();
            $table->foreign('window_id')->references('id')->on('windows');
            $table->integer('window_id')->change();
        });
    }
}
