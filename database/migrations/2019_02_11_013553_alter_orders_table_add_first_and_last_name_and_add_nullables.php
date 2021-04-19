<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersTableAddFirstAndLastNameAndAddNullables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function(Blueprint $table)
        {
            $table->string('email', 100)->after('id');
            $table->string('last_name', 100)->after('id');
            $table->string('first_name', 100)->after('id');

            $table->string('details')->default('')->change();
            $table->string('order_notes')->default('')->change();
            $table->string('po_number')->default('')->change();
            $table->string('installation_notes')->default('')->change();
            $table->integer('revision_number')->default(0)->change();

            $table->integer('parent_id')->unsigned()->nullable()->change();
            $table->integer('contact_id')->unsigned()->nullable()->change(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function(Blueprint $table)
        {
            $table->string('details')->change();
            $table->string('order_notes')->change();
            $table->string('po_number')->change();
            $table->string('installation_notes')->change();
            $table->integer('revised_by')->unsigned()->change();
            $table->integer('revision_number')->change();
            $table->datetime('revised_at')->change();

            $table->integer('parent_id')->unsigned()->change();
            $table->integer('root_id')->unsigned()->change();
            $table->integer('contact_id')->unsigned()->change();

            $table->dropColumn('email');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
        });
    }
}
