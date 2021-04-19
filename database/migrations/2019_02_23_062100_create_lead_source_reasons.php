<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadSourceReasons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_status_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reason')->nullable(false);
            $table->string('background_color')->nullable(false);
            $table->string('text_color')->nullable(false);
            $table->string('quote_status_id')->references('id')->on('lead_sources');
            $table->boolean('is_active')->default(1);

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
        Schema::dropIfExists('lead_source_reasons');
    }
}
