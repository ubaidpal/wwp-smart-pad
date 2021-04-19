<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteStatusStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_status_statuses', function (Blueprint $table) {
            $table->increments('id');

            $table->text('notes')->nullable();

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');
            
            $table->integer('quote_status_id')->unsigned();
            $table->foreign('quote_status_id')->references('id')->on('quote_statuses');

            $table->integer('quote_status_reason_id')->nullable()->default(null)->unsigned();
            $table->foreign('quote_status_reason_id')->references('id')->on('quote_status_reasons');

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
        Schema::dropIfExists('quote_status_statuses');
    }
}
