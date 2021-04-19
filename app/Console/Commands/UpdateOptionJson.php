<?php

namespace App\Console\Commands;

use App\Classes\ProductOptionJson;
use App\Models\Product;
use Illuminate\Console\Command;

class UpdateOptionJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:optionjson {Id}';

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
        $this->info('Setting Product Options Again.');
        $product_id = $this->argument('Id');
        //
        $json = ProductOptionJson::getOptionJson($product_id);
        $info = Product::find($product_id);
        $info->options = json_encode($json);
        $info->save();
        $this->info('Setting Product Options Completed.');
    }
}
