<?php

namespace App\Console\Commands;

use App\Classes\ProductOption;
use App\Models\Product;
use Illuminate\Console\Command;

class ParseProductJSON extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:json';

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
        $this->info('Setting Up Product JSON Parse Start.');
        $products = Product::select('id','options')->whereBetween('id', [4501, 5147])->orderBy('id', 'DESC')->get();
        foreach ($products  as $product){
            ProductOption::dumpOptions($product->id,$product->options);
            $this->info('Product ID '.$product->id.' JSON Completed.');
        }
        $this->info('Product JSON Parse End.');
    }
}
