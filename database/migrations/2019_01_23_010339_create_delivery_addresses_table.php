<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');


            $table->integer('account_id')->nullable()->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->integer('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');
            $table->integer('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

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
        Schema::dropIfExists('delivery_addresses');
    }
}
