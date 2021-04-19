<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Configurable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurable', function (Blueprint $table) {
            $table->increments('id');

            $table->string('group');
            $table->string('name')->default('');
            $table->string('type')->default('')->nullable();
            $table->longText('value')->nullable();
            $table->string('value_type')->default('')->nullable();
            $table->string('description')->default('')->nullable();
            $table->longText('other_fields')->nullable();

            $table->string('configurable_type')->default('');
            $table->index('configurable_type');
            $table->integer('configurable_id')->unsigned();
            $table->index('configurable_id');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

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
        Schema::dropIfExists('configurable');
    }
}
