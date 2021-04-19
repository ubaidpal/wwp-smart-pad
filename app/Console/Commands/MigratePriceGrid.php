<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePriceGrid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:pricegrid';

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
        $this->info('Process Started Price Grid Migration....');

        //
        DB::statement("SET foreign_key_checks=0");
        DB::table('price_grids')->truncate();
        DB::statement("SET foreign_key_checks=1");

        //
        DB::table('products')->orderBy('id')->chunk(100, function ($products) {

            foreach ($products as $product) {

                DB::connection('mysql2')->table('BookCharts')->orderBy('BookChartsID')
                    ->where('ProductID',$product->id)->chunk(100, function ($price_grid) {

                        $data    = [];
                        $grid_json = array();
                        $product_id = 0;
                        foreach ($price_grid as $grid){
                            $data[] = [
                                'width' => $grid->Width,
                                'height' => $grid->Height,
                                'price' => $grid->Price,
                                'product_id' => $grid->ProductID
                            ];
                            $product_id = $grid->ProductID;
                            $data = [];
                            if($grid->Width >= 0 && $grid->Width != null){
                                $data['width'] = $grid->Width;
                            }
                            if($grid->Height >= 0 && $grid->Height != null){
                                $data['height'] = $grid->Height;
                            }
                            $data['price'] = $grid->Price;
                            $grid_json[] = (Object)$data;
                        }

                        DB::table('products')->where('id', $product_id)->update(['price_grid' => json_encode($grid_json) ]);
                        DB::table('price_grids')->insert($data);

                    });
            }
        });

        $this->info('Process Ended Price Grid Migration.');
    }

}
