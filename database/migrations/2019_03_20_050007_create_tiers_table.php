<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
            $table->boolean('is_active')->default(1);

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');


            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
            // $table->boolean('is_deleted')->default(0);
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
        Schema::dropIfExists('tiers');
    }
}
