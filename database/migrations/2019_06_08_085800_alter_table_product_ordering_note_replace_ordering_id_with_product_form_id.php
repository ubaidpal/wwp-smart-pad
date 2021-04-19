<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProductOrderingNoteReplaceOrderingIdWithProductFormId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_ordering_notes', function (Blueprint $table) {
            $table->integer('ordering_form_details_id')->nullable()->unsigned()->after('id');
            $table->foreign('ordering_form_details_id')->references('id')->on('ordering_form_details');

            $table->dropForeign('product_ordering_notes_product_ordering_id_foreign');
            $table->dropColumn('product_ordering_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->integer('product_ordering_id')->nullable()->unsigned();
        $table->foreign('product_ordering_id')->references('id')->on('product_ordering');

        $table->dropColumn('ordering_form_details_id');
    }
}
