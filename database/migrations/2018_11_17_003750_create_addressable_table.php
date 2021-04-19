<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addressable', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_address')->default('');
            $table->index('full_address');
            $table->string('addressable_type')->default('');
            $table->index('addressable_type');
            $table->integer('addressable_id')->unsigned();
            $table->index('addressable_id');
            $table->string('address')->default('');
            $table->string('city')->default('');
            $table->integer('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('states');
            $table->string('zip')->default('');
            $table->string('development')->default('')->nullable();
            $table->string('lat')->default('');
            $table->string('lng')->default('');
            $table->string('type')->default('')->nullable();
            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries');

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
        Schema::dropIfExists('addressable');
    }
}
