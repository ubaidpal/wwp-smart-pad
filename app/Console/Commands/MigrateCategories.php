<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:categories';

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

        $this->info('Process Started Category Migration....');

        //
        DB::statement("SET foreign_key_checks=0");
        DB::table('categories')->truncate();
        DB::statement("SET foreign_key_checks=1");

        //
        DB::connection('mysql2')->table('Category')->where('account_id',0)->orderBy('Name','ASC')->orderBy('account_id','ASC')->orderBy('Seq','ASC')->chunk(100, function ($categories) {

            $data    = [];
            foreach ($categories as $category) {

                $data[] = [
                    'id' => $category->CategoryID,
                    'name' => $category->Name,
                    'is_active' => ($category->Active == 'Y')? 1:0,
                ];

            }

            DB::table('categories')->insert($data);

        });

        $this->info('Process Ended Category Migration.');

    }
}
