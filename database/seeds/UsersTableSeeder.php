<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Schema::hasTable('users')) {
            $this->dropForeignKeys();
            DB::table('users')->truncate();
        }
        $this->reCreateForeignKeys();
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Super',
            'email' => 'super@spp2.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'name' => 'country',
            'email' => 'country@spp2.com',
            'password' => bcrypt('password'),
            'role_id' => 2,
        ]);
        DB::table('users')->insert([
            'id' => 3,
            'name' => 'manager',
            'email' => 'manager@spp2.com',
            'password' => bcrypt('password'),
            'role_id' => 3
        ]);
    }

    private function dropForeignKeys(){

        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign('accounts_created_by_foreign');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign('accounts_updated_by_foreign');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_role_id_foreign');
        });


    }

    private function  reCreateForeignKeys(){

        // re-Create foreign keys
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users');
        });
        Schema::table('accounts', function (Blueprint $table) {
            $table->foreign('updated_by')->references('id')->on('users');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('role_permission');
        });
    }
}
