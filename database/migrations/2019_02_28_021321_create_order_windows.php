<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderWindows extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_windows', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 50);
            $table->integer('order_id')->nullable()->unsigned();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->text('notes')->nullable();
            
            // STANDARD
            $table->integer('window_id')->nullable()->unsigned();
            $table->foreign('window_id')->references('id')->on('windows');
            $table->integer('quantity')->unsigned()->nullable();
            $table->string('site_mount')->nullable();
            $table->integer('left_extension')->unsigned()->nullable();
            $table->integer('window_width')->unsigned()->nullable();
            $table->integer('right_extension')->unsigned()->nullable();
            $table->integer('width')->unsigned()->nullable();
            $table->integer('above_window')->unsigned()->nullable();
            $table->integer('window_height')->unsigned()->nullable();
            $table->integer('below_window')->unsigned()->nullable();
            $table->integer('height')->unsigned()->nullable();

            $table->string('fix_type')->nullable();
            $table->string('control', 50)->nullable();
            $table->integer('install_type_id')->nullable()->unsigned();
            $table->foreign('install_type_id')->references('id')->on('install_types');
            // END OF STANDARD
            // MANUAL
            $table->string('details', 255)->nullable();
            $table->integer('cost_price')->unsigned()->nullable();
            $table->integer('sell_price')->unsigned()->nullable();
            // END OF MANUAL

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');
            
            $table->integer('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->nullable()->unsigned();
            $table->foreign('updated_by')->references('id')->on('employees');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_windows');
    }
}
