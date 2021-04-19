<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuoteDocumentsChangeDocumentCategoryForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_documents', function (Blueprint $table) {
            $table->dropForeign('quote_documents_document_category_id_foreign');
            $table->dropColumn('document_category_id');

            $table->integer('product_document_category_id')->nullable()->unsigned();
            $table->foreign('product_document_category_id')->references('id')->on('product_document_categories');
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
            $table->dropForeign('quote_documents_product_document_category_id_foreign');
            $table->dropColumn('product_document_category_id');

            $table->integer('document_category_id')->unsigned();
            $table->foreign('document_category_id')->references('id')->on('document_categories');
        });
    }
}
