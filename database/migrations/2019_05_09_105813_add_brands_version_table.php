<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandsVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_version', function (Blueprint $table) {
            $table->increments('id');
            $table->string('version');
            $table->integer('brand_id')->unsigned()->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('active')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('brand_version');
    }
}
