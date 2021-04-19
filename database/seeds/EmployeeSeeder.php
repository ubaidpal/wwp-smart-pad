<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            'id' => 1,
            'first_name' => 'Kevin',
            'last_name' => 'Cotongco',
            'username' => 'glacialblade',
            'password' => bcrypt('password'),
            'text_color' => '#FFFFFF',
            'background_color' => '#333333',
            'email' => 'kevin@smartpadpro.com.au',
            'email_signature' => '',
            'notes' => '',
            'status' => 'active',
            'is_appointee' => true,
            'maximum_discount' => 10,
            'account_id' => 1,
            'created_by' => null,
            'updated_by' => null
        ]);

        DB::table('employees')->insert([
            'id' => 2,
            'first_name' => 'Aaron',
            'last_name' => 'Lecornu',
            'username' => 'aaronl',
            'password' => bcrypt('password'),
            'text_color' => '#FFFFFF',
            'background_color' => '#333333',
            'email' => 'aaron@smartpadpro.com.au',
            'email_signature' => '',
            'notes' => '',
            'status' => 'active',
            'is_appointee' => true,
            'maximum_discount' => 10,
            'account_id' => 2,
            'created_by' => null,
            'updated_by' => null
        ]);

        DB::table('employees')->insert([
            'id' => 3,
            'first_name' => 'Kevin',
            'last_name' => 'Cotongco',
            'username' => 'test',
            'password' => bcrypt('password'),
            'text_color' => '#FFFFFF',
            'background_color' => '#333333',
            'email' => 'test@smartpadpro.com.au',
            'email_signature' => '',
            'notes' => '',
            'status' => 'active',
            'is_appointee' => true,
            'maximum_discount' => 10,
            'account_id' => 3,
            'created_by' => null,
            'updated_by' => null
        ]);

        // for($i=0; $i<10; $i++)
        // {
        //     $faker = \Faker\Factory::create();

        //     $id = 10000+$i;
        //     $result = DB::table('employees')->insert([
        //         'id' => $id,
        //         'first_name' => $faker->firstName,
        //         'last_name' => $faker->lastName,
        //         'username' => $faker->unique()->username,
        //         'password' => bcrypt('password'),
        //         'text_color' => $faker->unique()->hexcolor,
        //         'background_color' => $faker->unique()->hexcolor,
        //         'email' => $faker->unique()->safeEmail,
        //         'email_signature' => '',
        //         'notes' => '',
        //         'status' => 'active',
        //         'is_appointee' => true,
        //         'maximum_discount' => 10,
        //         'account_id' => 1,
        //         'created_by' => null,
        //         'updated_by' => null
        //     ]);

        //     DB::table('employee_roles')->insert([
        //         'employee_id' => $id,
        //         'role_id' => 1
        //     ]);
        // }
    }
}
