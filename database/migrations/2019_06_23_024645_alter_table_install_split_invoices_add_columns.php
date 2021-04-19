<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableInstallSplitInvoicesAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_install_split_invoices', function (Blueprint $table) {
            $table->longText('all_split_totals');
            $table->decimal('total_payments', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_install_split_invoices', function (Blueprint $table) {
            $table->dropColumn('all_split_totals');
            $table->dropColumn('total_payments');
        });
    }
}
