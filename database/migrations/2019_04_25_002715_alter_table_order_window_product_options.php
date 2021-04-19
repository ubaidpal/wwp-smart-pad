<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrderWindowProductOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_window_product_option', function (Blueprint $table) {
            $table->string('group_id')->nullable()->after('product_option_id');
            $table->string('name')->nullable()->after('group_id');
            $table->string('select_label')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_window_product_option', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropColumn('name');
            $table->dropColumn('select_label');
        });
    }
}
