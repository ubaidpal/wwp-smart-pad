<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('task');
            $table->date('due_date')->nullable()->index();
            $table->date('date_completed')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->integer('order')->default(0);

            $table->integer('parent_id')->nullable()->unsigned();
            $table->foreign('parent_id')->references('id')->on('tasks');

            $table->integer('task_group_id')->unsigned();
            $table->foreign('task_group_id')->references('id')->on('taskable');

            $table->integer('account_id')->nullable()->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->nullable()->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');

            $table->integer('updated_by')->nullable()->unsigned();
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
        Schema::dropIfExists('tasks');
    }
}
