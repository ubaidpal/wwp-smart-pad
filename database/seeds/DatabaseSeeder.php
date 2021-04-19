<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesBackendTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CurrenciesAndCountries::class);
        $this->call(StatesTableSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(TestDocumentTableSeeder::class);
        $this->call(DefaultSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ProductOptionSeeder::class);
        $this->command->info('Super User created with username: super@spp2.com and password: password');
    }
}
