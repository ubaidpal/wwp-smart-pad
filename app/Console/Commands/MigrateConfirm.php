<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateConfirm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:confirm';

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
        $data = \DB::connection('mysql2')->select('SELECT count(*) as total,BrandID FROM ProductDetails WHERE BrandID IN (SELECT `BrandID` FROM Brand WHERE `country_code` LIKE \'AU\') GROUP by BrandID ORDER BY BrandID ASC');
        foreach($data as $info){
            $product = \DB::table('products')->where('brand_id', $info->BrandID)->count();
            if($info->total != $product){
                \DB::table('products')->where('brand_id', $info->BrandID)->delete();
                //DELETE FROM `products` WHERE `brand_id` = 158
                $this->info('New DB : '.$product.'--Old DB---'.$info->total.'--Brand--'.$info->BrandID);
            }
        }
    }
}
