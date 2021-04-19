<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDocumentCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_document_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('category');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->boolean('is_active')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_document_categories');
    }
}
