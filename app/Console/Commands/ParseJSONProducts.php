<?php

namespace App\Console\Commands;

use App\Classes\ProductOption;
use App\Models\Product;
use Illuminate\Console\Command;

class ParseJSONProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse3:json {number}';

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
        $start = $this->argument('number');
        $end = $start + 20;
        //
        $this->info('Setting Up Product JSON Parse Start.----'.$start.'----'.$end);
        $products = Product::select('id','options')->whereBetween('id', [$start, $end])->orderBy('id', 'DESC')->get();
        foreach ($products  as $product){
            ProductOption::dumpOptions($product->id,$product->options);
            $this->info('Product ID '.$product->id.' JSON Completed.');
        }
        $this->info('Product JSON Parse End.---'.$start.'----'.$end);
    }
}
