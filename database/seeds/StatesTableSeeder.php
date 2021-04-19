<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$countriesFix = [
			'Taiwan, Province Of China' => 'Taiwan', 
			'Macedonia, the Former Yugoslav Republic Of' => 'Macedonia (the former Yugoslav Republic of)',
			'Congo, The Democratic Republic Of The' => 'Congo (Democratic Republic of the)',
			'Iran, Islamic Republic Of' => 'Iran (Islamic Republic Of)',
			'Korea, Democratic People\'s Republic Of' => 'Korea (Democratic People\'s Republic Of)',
			'Palestinian Territory, Occupied' => 'Palestine, State of',
			'Venezuela, Bolivarian Republic of' => 'Venezuela (Bolivarian Republic of)',
			'Moldova, Republic of' => 'Moldova (Republic of)',
			'Bolivia, Plurinational State Of' => 'Bolivia (Plurinational State Of)',
			'Micronesia, Federated States Of' => 'Micronesia (Federated States Of)',
			'Cape Verde' => 'Cabo Verde',
			'Korea, Republic of' => 'Korea (Republic of)',
		];
		
		$data    = [];
        $content = explode("\n", \File::get(database_path('seeds/states.txt')));
		foreach($content as $key=>$state)
		{
			if($key > 0 && $state)
			{
				$state = explode("\t", $state);
				$state[0] = !isset($countriesFix[$state[0]]) ? $state[0] : $countriesFix[$state[0]];
				$country = DB::table('countries')->where('name', $state[0])->orWhere('native_name', $state[0])->first();

				$data[] = [
					'name' => $state[2],
					'code' => explode('-', $state[1])[1],
					'country_id' => $country->id
				];
			}
		}

		DB::statement("SET foreign_key_checks=0");
		DB::table('states')->truncate();
		DB::statement("SET foreign_key_checks=1");
		
		DB::table('states')->insert($data);
    }
}

		