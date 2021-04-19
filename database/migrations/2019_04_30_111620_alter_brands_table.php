<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('is_active');
            $table->string('address')->nullable()->after('company_name');
            $table->string('company_address')->nullable()->after('address');
            $table->string('company_phone')->nullable()->after('company_address');
            $table->string('email')->nullable()->after('company_phone');
            $table->string('city')->nullable()->after('email');
            $table->string('state')->nullable()->after('city');
            $table->string('company_fax')->nullable()->after('state');
            $table->string('postal_code')->nullable()->after('company_fax');

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
            $table->dropColumn('company_name');
            $table->dropColumn('address');
            $table->dropColumn('company_address');
            $table->dropColumn('company_phone');
            $table->dropColumn('email');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('company_fax');
            $table->dropColumn('postal_code');
        });
    }
}
