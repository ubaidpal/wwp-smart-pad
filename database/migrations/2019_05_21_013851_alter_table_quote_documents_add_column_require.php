<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableQuoteDocumentsAddColumnRequire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_document_categories', function (Blueprint $table) {
            $table->string('required')->nullable()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_document_categories', function (Blueprint $table) {
            $table->dropColumn('required');
        });
    }
}
