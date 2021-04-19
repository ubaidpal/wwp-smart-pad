<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDocumentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('documents')->insert([[
            'id' => 1,
            'filename' => 'To be with you.csv',
            'name' => 'To be with you',
            'type' => 'excel',
            'extension' => 'csv',
            'mime_type' => 'text/csv',
            'size' => '12345',
            'account_id' => 3,
            'created_by' => 1,
            'updated_by' => 1,
        ], [
            'id' => 2,
            'filename' => 'Undertow.csv',
            'name' => 'Undertow',
            'type' => 'excel',
            'extension' => 'csv',
            'mime_type' => 'text/csv',
            'size' => '12345',
            'account_id' => 3,
            'created_by' => 1,
            'updated_by' => 1,
        ], [
            'id' => 3,
            'filename' => 'Nothing but love.csv',
            'name' => 'Nothing but love',
            'type' => 'excel',
            'extension' => 'csv',
            'mime_type' => 'text/csv',
            'size' => '12345',
            'account_id' => 3,
            'created_by' => 1,
            'updated_by' => 1,
        ]]);
    }
}
