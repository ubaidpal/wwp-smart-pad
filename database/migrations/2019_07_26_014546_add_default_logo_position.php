<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultLogoPosition extends Migration
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
            $hasLogoPosition = DB::select(DB::raw("SELECT * FROM configurable WHERE `group` = 'quotes' AND name = 'logo_position' AND configurable_id = $company->id"));

            if(count($hasLogoPosition) == 0) {
                DB::table('configurable')->insert([
                    'group' => 'quotes',
                    'name' => 'logo_position',
                    'value' => 'Grid 2',
                    'value_type' => '',
                    'description' => '',
                    'other_fields' => '',
                    'configurable_type' => 'App\Models\Company\Company',
                    'configurable_id' => $company->id,
                    'created_by' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                DB::table('configurable')->insert([
                    'group' => 'orders',
                    'name' => 'logo_position',
                    'value' => 'Grid 2',
                    'value_type' => '',
                    'description' => '',
                    'other_fields' => '',
                    'configurable_type' => 'App\Models\Company\Company',
                    'configurable_id' => $company->id,
                    'created_by' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                DB::table('configurable')->insert([
                    'group' => 'measure_and_install',
                    'name' => 'logo_position',
                    'value' => 'Grid 2',
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
