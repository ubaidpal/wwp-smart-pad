<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadSources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(false);
            $table->string('background_color')->nullable(false);
            $table->string('text_color')->nullable(false);
            $table->integer('account_id')->nullable()->default(null)->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->boolean('immutable')->default(0);
            $table->boolean('is_active')->default(1);

            // $table->foreign('order')->references('id')->on('orders');

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
        Schema::dropIfExists('lead_sources');
    }
}
