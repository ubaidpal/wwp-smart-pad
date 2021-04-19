<?php

namespace App\Console\Commands;

use App\Models\PriceGrid;
use App\Models\Product;
use Illuminate\Console\Command;

class UpdatePriceGrid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:price {product_id}';

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
        //
        $this->info('Price Gird Fixing...');
        $product_id = $this->argument('product_id');
        $grid     = PriceGrid::where('product_id', $product_id)->whereNull('product_sub_option_id')->get();
        $gridJson = [];
        foreach ($grid as $row) {
            $data = [];
            if($row->width >= 0 && $row->width != NULL) {
                $data[ 'width' ] = $row->width;
            }
            if($row->height >= 0 && $row->height != NULL) {
                $data[ 'height' ] = $row->height;
            }
            $data[ 'price' ] = $row->price;
            $gridJson[]      = $data;
        }
        $product          = Product::find($product_id);
        $product->price_grid = \GuzzleHttp\json_encode($gridJson);
        $product->save();
        $this->info('Price Gird Fixed...');
        //dd(\GuzzleHttp\json_encode($gridJson));
    }
}
