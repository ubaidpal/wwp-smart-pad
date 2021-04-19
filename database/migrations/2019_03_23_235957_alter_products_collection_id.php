<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsCollectionId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['collection_id']);
            $table->dropColumn('collection_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('collection_id')->unsigned()->nullable()->after('brand_id');
            $table->foreign('collection_id')->references('id')->on('collections')->on_update('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
