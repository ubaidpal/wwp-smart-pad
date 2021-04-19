<?php

namespace App\Console\Commands;

use App\Classes\ProductOptionJson;
use App\Models\Product;
use App\Models\ProductOptionConstraints;
use App\Models\ProductOptionSelects;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConstraintsCompile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'constraints:compile {product_id}';

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
        $this->info('Constraints Fixing...');
        $product_id = $this->argument('product_id');
        $product = Product::find($product_id);
        $options = json_decode($product->options);
        foreach ($options as $option){
            if($option->type == 'group'){
                $sub_options = ProductOptionSelects::select('id')->where('product_option_id',$option->linked_sub_id)->get();
                $constraints = DB::table('product_option_constraint_actions')->whereIn('sub_option_id', $sub_options)->first();
                if($constraints){
                    $const = ProductOptionConstraints::find($constraints->option_constraint_id);
                    if($option->linked_sub_id  != $const->product_option_id) {
                        $const->product_option_id = $option->linked_sub_id;
                        $const->save();
                    }
                }
            }
        }
        $json = ProductOptionJson::getOptionJson($product_id);
        $info = Product::find($product_id);
        $info->options = json_encode($json);
        $info->save();
        $this->info('Review Product Now...');
    }
}
