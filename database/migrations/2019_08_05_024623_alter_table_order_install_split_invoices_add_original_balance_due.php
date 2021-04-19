<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrderInstallSplitInvoicesAddOriginalBalanceDue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_install_split_invoices', function (Blueprint $table) {
            $table->decimal('original_balance_due', 10, 2);
            $table->longText('surcharges')->nullable();
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
            $table->dropColumn('original_balance_due');
            $table->dropColumn('surcharges');
        });
    }
}
