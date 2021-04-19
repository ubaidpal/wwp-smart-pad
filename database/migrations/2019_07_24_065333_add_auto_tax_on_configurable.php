<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutoTaxOnConfigurable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $result = DB::select(DB::raw('SELECT * FROM accounts'));
        
        foreach($result as $account) {
            $hasAutoTax = DB::select(DB::raw("SELECT * FROM configurable WHERE `group` = 'general_settings' AND name = 'auto_tax' AND configurable_id = $account->id"));

            if(count($hasAutoTax) == 0) {
                DB::table('configurable')->insert([
                    'group' => 'general_settings',
                    'name' => 'auto_tax',
                    'value' => 1,
                    'value_type' => '',
                    'description' => '',
                    'other_fields' => '',
                    'configurable_type' => 'App\Models\Account\Account',
                    'configurable_id' => $account->id,
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
