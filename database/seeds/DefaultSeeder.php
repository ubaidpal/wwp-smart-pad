<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach([1,2,3] as $id)
        {
            DB::table('order_statuses')->insert([
                'name' => 'New Order',
                'background_color' => '#0db7dd',
                'text_color' => 'white',
                'account_id' => $id,
                'required' => true,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            DB::table('order_statuses')->insert([
                'name' => 'Completed',
                'background_color' => 'green',
                'text_color' => 'white',
                'account_id' => $id,
                'required' => true,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            DB::table('appointment_statuses')->insert([
                'name' => 'Confirmed',
                'background_color' => 'green',
                'text_color' => 'white',
                'account_id' => $id,
                'required' => true,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ]);
            DB::table('appointment_statuses')->insert([
                'name' => 'Declined',
                'background_color' => 'red',
                'text_color' => 'white',
                'account_id' => $id,
                'required' => true,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ]);
            DB::table('appointment_statuses')->insert([
                'name' => 'Pending',
                'background_color' => 'gray',
                'text_color' => 'white',
                'account_id' => $id,
                'required' => true,
                'is_active' => true,
                'created_by' => 1,
                'updated_by' => 1,
            ]);

            DB::table('companies')->insert([
                'name' => 'Smartpad Pro',
                'email' => 'kevin@smartpadpro.com.au',
                'timezone' => 'Asia/Hong_Kong',
                'website' => 'http://smartpadpro.com.au',
                'abn' => '123',
                'acn' => '456',
                'main' => true,
                'account_id' => $id,
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }
}

