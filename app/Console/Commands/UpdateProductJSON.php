<?php

namespace App\Console\Commands;

use App\Classes\ProductOptionJson;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateProductJSON extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:json';

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
        $this->info('Setting Up Product Options JSON Again.');
        //$products = DB::table("products")->whereRaw('MONTH(created_at)',7)->get();
        $products = Product::whereBetween('id', [5000, 5999])->get();
        foreach ($products  as $product){
            $json = ProductOptionJson::getOptionJson($product->id);
            $info = Product::find($product->id);
            $info->options = json_encode($json);
            $info->save();
            $this->info('Product ID '.$product->id.' JSON Completed.');
        }
//        Product::select('id')->chunk(10, function ($products) {
//            foreach ($products  as $product){
//                $json = ProductOptionJson::getOptionJson($product->id);
//                $info = Product::find($product->id);
//                $info->options = json_encode($json);
//                $info->save();
//                $this->info('Product ID '.$product->id.' JSON Completed.');
//            }
//        });

        $this->info('Setting Product Options Completed.');
    }
}
