<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionConstraintActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_constraint_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->Integer('sub_option_id');
            $table->Integer('option_id');
            $table->integer('option_constraint_id')->unsigned();
            $table->foreign('option_constraint_id')->references('id')->on('product_option_constraints')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('option_constraint_actions');
    }
}
