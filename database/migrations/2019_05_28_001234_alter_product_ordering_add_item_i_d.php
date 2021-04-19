<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductOrderingAddItemID extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_ordering', function (Blueprint $table) {
            $table->integer('item_id')->nullable(true)->after('product_id');
            $table->integer('is_workroom')->default(false)->after('item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_ordering', function (Blueprint $table) {
            $table->dropColumn('item_id');
            $table->dropColumn('is_workroom');
        });
    }
}
