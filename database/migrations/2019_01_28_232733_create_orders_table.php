<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('unique_id', 150);
            $table->unique(['unique_id', 'account_id']);
            $table->string('details', 150);
            $table->string('po_number', 150);
            $table->text('order_notes');
            $table->text('installation_notes');
            $table->longText('signature')->nullable();

            $table->integer('referred_by_id')->nullable()->unsigned();
            $table->foreign('referred_by_id')->references('id')->on('referred_by');
            
            $table->integer('order_status_id')->unsigned();
            $table->foreign('order_status_id')->references('id')->on('order_statuses');

            $table->boolean('is_locked')->default(false);

            $table->boolean('is_quote')->default(true)->index();
            $table->boolean('is_archived')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();

            // The contact that the quote is connected.
            $table->integer('contact_id')->unsigned();
            $table->foreign('contact_id')->references('id')->on('contacts');

            // Sales Representative that handles the quote.
            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');

            // Mail Status
            $table->string('mail_status')->default('pending')->nullable();

            // Payment Status
            $table->string('payment_status')->default('')->nullable();
            $table->string('marked_payment_status')->default('')->nullable();

            // Revision columns.
            $table->integer('revision_number');

            // Quote Parent of the order
            $table->integer('parent_id')->unsigned();
            $table->foreign('parent_id')->references('id')->on('orders');

            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->integer('account_id')->unsigned();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('created_by')->unsigned();
            $table->foreign('created_by')->references('id')->on('employees');
            
            $table->integer('updated_by')->unsigned();
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
        Schema::dropIfExists('orders');
    }
}
