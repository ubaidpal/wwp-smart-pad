<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductDocumentCategoriesUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_document_categories', function (Blueprint $table) {
            $table->unique(['category', 'account_id'], 'product_document_categories_per_account_unique');
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
            $table->dropUnique('product_document_categories_per_account_unique');
        });
    }
}
