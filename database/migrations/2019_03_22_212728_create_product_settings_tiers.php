<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSettingsTiers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_settings_tiers', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('product_setting_id')->unsigned();
            $table->foreign('product_setting_id')->references('id')->on('product_settings');
            $table->integer('tier_id')->unsigned();
            $table->foreign('tier_id')->references('id')->on('tiers');
            $table->integer('amount')->nullable(false);

            $table->integer('account_id')->nullable()->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->integer('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');
            $table->integer('updated_by')->nullable()->unsigned();
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
        Schema::dropIfExists('product_settings_tiers');
    }
}
