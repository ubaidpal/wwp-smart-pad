<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderPaymentTransactionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->string('transaction_id')->nullable(true)->after('payment_gateway');
            $table->string('transaction_status')->nullable(true)->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_payments', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
            $table->dropColumn('transaction_status');
        });
    }
}
