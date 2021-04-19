<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taggable', function (Blueprint $table) {
            $table->increments('id');

            $table->string('tag');

            $table->string('taggable_type')->default('');
            $table->index('taggable_type');

            $table->integer('taggable_id')->unsigned();
            $table->index('taggable_id');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            
            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

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
        Schema::dropIfExists('taggable');
    }
}
