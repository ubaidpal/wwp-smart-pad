<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentable', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            
            $table->string('documentable_type');
            $table->index('documentable_type');

            $table->integer('documentable_id')->unsigned();
            $table->index('documentable_id');

            $table->integer('document_id')->unsigned();
            $table->foreign('document_id')->references('id')->on('documents');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentable');
    }
}
