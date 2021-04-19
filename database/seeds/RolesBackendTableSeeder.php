<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolesBackendTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('role_permission')->insert([
            'id' => 1,
            'name' => 'Administrator',
            'permissions' => '*',
        ]);
        DB::table('role_permission')->insert([
            'id' => 2,
            'name' => 'Country Administrator',
            'permissions' => 'users_country*',
        ]);
        DB::table('role_permission')->insert([
            'id' => 3,
            'name' => 'Manager',
            'permissions' => 'products,items,dealers,config*',
        ]);
    }
}
