<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderingFormDetailsAddOrderSentAndEmailStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordering_form_details', function (Blueprint $table) {
            $table->boolean('is_order_sent')->default(false)->after('category_id');
            $table->string('email_status')->after('is_order_sent');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordering_form_details', function (Blueprint $table) {
            $table->dropColumn('is_order_sent');
            $table->dropColumn('email_status');
        });
    }
}
