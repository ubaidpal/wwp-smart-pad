<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateBrands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:brands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->info('Process Started Brand Migration....');

        //
        DB::statement("SET foreign_key_checks=0");
        DB::table('brands')->truncate();
        DB::statement("SET foreign_key_checks=1");

        //
        DB::connection('mysql2')->table('Brand')->where('country_code' ,'AU')->orderBy('BrandID')->chunk(100, function ($brands) {

            $data    = [];
            foreach ($brands as $brand) {

                $country_code = $brand->country_code;
                if($country_code == 'UK'){
                    $country_code = 'GB';
                }

                $country = DB::table('countries')->where('iso2_code', $country_code)->first();
                $data[] = [
                    'id' => $brand->BrandID,
                    'name' => $brand->Name,
                    'country_id' => $country->id,
                    'is_fabric' => $brand->is_fabric,
                    'comment' => $brand->notes
                ];

            }

            DB::table('brands')->insert($data);

        });

        $this->info('Process Ended Brand Migration.');

    }
}
