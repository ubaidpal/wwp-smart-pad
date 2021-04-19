<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DefaultDespotiBalanceOnOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $result = DB::select(DB::raw('SELECT * FROM companies'));
        
        foreach($result as $company) {
            $showDepositDue = DB::select(DB::raw("SELECT * FROM configurable WHERE `group` = 'orders' AND name = 'show_deposit_due' AND configurable_id = $company->id"));

            if(count($showDepositDue) == 0) {
                DB::table('configurable')->insert([
                    'group' => 'orders',
                    'name' => 'show_deposit_due',
                    'value' => 1,
                    'value_type' => '',
                    'description' => '',
                    'other_fields' => '',
                    'configurable_type' => 'App\Models\Company\Company',
                    'configurable_id' => $company->id,
                    'created_by' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            $showBalanceDue = DB::select(DB::raw("SELECT * FROM configurable WHERE `group` = 'orders' AND name = 'show_balance_due' AND configurable_id = $company->id"));

            if(count($showBalanceDue) == 0) {
                DB::table('configurable')->insert([
                    'group' => 'orders',
                    'name' => 'show_balance_due',
                    'value' => 1,
                    'value_type' => '',
                    'description' => '',
                    'other_fields' => '',
                    'configurable_type' => 'App\Models\Company\Company',
                    'configurable_id' => $company->id,
                    'created_by' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
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
