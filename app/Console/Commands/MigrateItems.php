<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:items';

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
        $this->info('Reset Tables.');
        DB::statement("SET foreign_key_checks=0");
        DB::table('items')->truncate();
        DB::table('brand_version')->truncate();
        DB::statement("SET foreign_key_checks=1");
        $this->info('------------------------');

        /**/
        $this->info('Items Migration Started...');
        DB::connection('mysql2')->table('fabric_product')->orderBy('id')->chunk(100, function ($items) {
            $data    = [];
            foreach ($items as $item){
                $data[] = [
                    'name' => $item->name,
                    'fabric_repeat' => $item->repeat,
                    'fabric_width' => $item->width,
                    'price' => $item->price,
                    'category_id' => $item->category_id,
                    'brand_id' => $item->brand_id,
                    'active' => $item->is_active
                ];
            }
            DB::table('items')->insert($data);

        });
        $this->info('Brand Version Adjustment.');
        $versions = DB::connection('mysql2')->table('fabric_product')->groupBy('brand_id')->get();
        foreach ($versions as $item){

            $check = DB::connection('mysql2')->table('Brand')->where('BrandID',$item->brand_id)->count();
            if($check > 0){
                $version_id = DB::table('brand_version')->insertGetId([
                    'version' => 'Data Migration',
                    'brand_id' => $item->brand_id,
                    'active' => $item->is_active
                ]);
                DB::table('items')->where('brand_id', $item->brand_id)->update(['version_id' => $version_id ]);
            }

        }

        $this->info('Items Migration Ended.');
    }
}
