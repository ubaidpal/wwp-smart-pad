<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderingFormDetailsAddQuantity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordering_form_details', function (Blueprint $table) {
            $table->integer('quantity')->after('email_status')->unsigned();
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
            $table->dropColumn('quantity');
        });
    }
}
