<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([[
            'id' => 1,
            'account_id' => 1,
            'all_access' => true,
            'role' => 'Administrator',
        ], [
            'id' => 2,
            'account_id' => 2,
            'role' => 'Administrator',
            'all_access' => true,
        ], [
            'id' => 3,
            'account_id' => 3,
            'role' => 'Administrator',
            'all_access' => true,
        ]]);

        DB::table('employee_roles')->insert([
            'employee_id' => 1,
            'role_id' => 1
        ]);

        DB::table('employee_roles')->insert([
            'employee_id' => 2,
            'role_id' => 2
        ]);

        DB::table('employee_roles')->insert([
            'employee_id' => 3,
            'role_id' => 3
        ]);
    }
}
