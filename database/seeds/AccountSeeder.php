<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            'id' => 1,
            'email' => 'kevin@smartpadpro.com.au',
            'status' => 'active',
            'max_employees' => 3,
            'max_companies' => 3,
            'country_id' => 14,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        DB::table('accounts')->insert([
            'id' => 2,
            'email' => 'aaron@smartpadpro.com.au',
            'status' => 'active',
            'max_employees' => 3,
            'max_companies' => 3,
            'country_id' => 14,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        DB::table('accounts')->insert([
            'id' => 3,
            'email' => 'test@smartpadpro.com.au',
            'status' => 'active',
            'max_employees' => 3,
            'max_companies' => 3,
            'country_id' => 14,
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
