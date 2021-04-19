<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderChangeOrderNotesInstallationNotesMeasuresNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function ($table) {
            $table->text('order_notes')->change();
            $table->text('installation_notes')->change();
            $table->text('measure_notes')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function ($table) {
            $table->string('order_notes')->change();
            $table->string('installation_notes')->change();
            $table->string('measure_notes')->change();
        });
    }
}
