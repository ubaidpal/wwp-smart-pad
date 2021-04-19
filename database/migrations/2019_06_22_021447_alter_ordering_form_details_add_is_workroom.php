<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderingFormDetailsAddIsWorkroom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ordering_form_details', function (Blueprint $table) {
            $table->boolean('is_workroom')->default(false)->after('is_order_sent');
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
            $table->dropColumn('is_workroom');
        });
    }
}
