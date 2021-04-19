<?php

namespace App\Console\Commands;

use App\Http\Controllers\ProductMigrationController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:products';

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
        $this->info('Process Started Products Migration.');

        //
        DB::statement("SET foreign_key_checks=0");
        DB::table('products')->truncate();
        DB::statement("SET foreign_key_checks=1");

        //
        DB::connection('mysql2')->table('ProductDetails')->orderBy('ProductID')->chunk(100, function ($products) {

            $data    = [];
            foreach ($products as $product) {

                //
                if($product->CatID > 0){

                    $country_code = $product->country_code;
                    if($country_code == 'UK'){
                        $country_code = 'GB';
                    }

                    $country = DB::table('countries')->where('iso2_code', $country_code)->first();

                    $brand_count = DB::table('brands')->where('id', $product->BrandID)->count();
                    $cat_count = DB::table('categories')->where('id', $product->CatID)->count();

                    if($brand_count > 0){
                        if($cat_count > 0){
                            $data[] = [
                                'id' => $product->ProductID,
                                'name' => stripslashes($product->Name),
                                'brand_id' => $product->BrandID,
                                'country_id' => $country->id,
                                'category_id' => $product->CatID,
                                'price_structure' => $product->price_grid_type,
                                'square_meter_rate' => $product->square_metre_price,
                                'is_workroom' => $product->is_workroom
                            ];
                        }
                    }

                }
            }

            DB::table('products')->insert($data);

        });

        $this->info('Process Ended Products Migration.');

    }
}
