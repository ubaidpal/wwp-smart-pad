<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductMeasurements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_measurements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products');

            $table->integer('category_id')->unsigned()->nullable(true);
            $table->foreign('category_id')->references('id')->on('categories');

            $table->integer('in_reveal_height');
            $table->integer('in_reveal_width');
            $table->integer('outside_reveal_height');
            $table->integer('outside_reveal_width');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->boolean('is_active')->default(false);

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_measurement_others', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_measurement_id')->unsigned();
            $table->foreign('product_measurement_id')->references('id')->on('product_measurements');

            $table->string('name');
            $table->integer('in_reveal_height');
            $table->integer('in_reveal_width');
            $table->integer('outside_reveal_height');
            $table->integer('outside_reveal_width');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
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
        Schema::dropIfExists('product_measurement_others');
        Schema::dropIfExists('product_measurements');
    }
}
