<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDashboardCardsAddColumnFilter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashboard_cards', function(Blueprint $table) {
            $table->string('filter')->nullable()->after('inclusion_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dashboard_cards', function(Blueprint $table) {
            $table->dropColumn('filter');
        });
    }
}
