<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBrandCategoryProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('brands', function (Blueprint $table) {
            $table->renameColumn('active', 'is_active');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('active', 'is_active');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('status', 'is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->renameColumn('active', 'is_active');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->renameColumn('active', 'is_active');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('status', 'is_active');
        });
    }
}
