<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeasureInstallReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_measure_window_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('left_extension')->nullable()->default(0);
            $table->integer('window_width')->nullable()->default(0);
            $table->integer('right_extension')->nullable()->default(0);
            $table->integer('width')->nullable()->default(0);
            $table->integer('above_window')->nullable()->default(0);
            $table->integer('window_height')->nullable()->default(0);
            $table->integer('below_window')->nullable()->default(0);
            $table->integer('height')->nullable()->default(0);
            $table->text('notes')->nullable();
            $table->string('fix_type')->nullable()->default('');
            $table->string('controls')->nullable()->default('');
            $table->string('status')->nullable()->default('');
            
            $table->integer('install_type_id')->nullable()->unsigned();
            $table->foreign('install_type_id')->references('id')->on('install_types');

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->integer('order_window_id')->unsigned();
            $table->foreign('order_window_id')->references('id')->on('order_windows')->onDelete('cascade');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
        });

        Schema::create('order_measure_product_reports', function (Blueprint $table) {
            $table->increments('id');

            $table->text('notes');

            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->integer('order_window_id')->unsigned();
            $table->foreign('order_window_id')->references('id')->on('order_windows');

            $table->integer('order_window_product_id')->unsigned();
            $table->foreign('order_window_product_id')->references('id')->on('order_window_products');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_measure_window_reports');
        Schema::dropIfExists('order_measure_product_reports');
    }
}
