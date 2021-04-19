<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCompanySurcharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_surcharges', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            // Override Product or Total
            $table->string('surcharge_type');

            $table->decimal('value', 10, 2);
            $table->string('value_type');

            $table->integer('company_id')->nullable(true)->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->integer('account_id')->nullable(true)->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
        });

        Schema::create('company_surcharge_brands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_surcharge_id')->index();
            $table->integer('brand_id')->index();
            $table->timestamps();
        });

        Schema::create('company_surcharge_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_surcharge_id')->index();
            $table->integer('category_id')->index();
            $table->timestamps();
        });

        Schema::create('company_surcharge_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_surcharge_id')->index();
            $table->integer('product_id')->index();
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
        Schema::dropIfExists('company_surcharges');
        Schema::dropIfExists('company_surcharge_brands');
        Schema::dropIfExists('company_surcharge_categories');
        Schema::dropIfExists('company_surcharge_products');
    }
}
