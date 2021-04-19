<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DealerDefaultSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dealer:default {Id}';

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
        $this->info('Default Dealer Setting Start.');
        //
        $account_id = $this->argument('Id');
        $user_id = 1;
        $this->info('Default Dealer Setting Start.');
        //CONFIGURABLE Settings
        DB::table('configurable')->insert(
            array(
                array(
                    'group' => 'scheduler_date_range',
                    'name' => 'start',
                    'value' => '08:00',
                    'configurable_type' => 'App\Models\Account\Account',
                    'configurable_id' => $account_id,
                    'created_by' => $user_id
                ),
                array(
                    'group' => 'scheduler_date_range',
                    'name' => 'end',
                    'value' => '17:00',
                    'configurable_type' => 'App\Models\Account\Account',
                    'configurable_id' => $account_id,
                    'created_by' => $user_id
                )
            )
        );
        $date_format = ['php' => 'd/m/Y', 'js' => 'dd/mm/YYYY'];
        $time_format = ['php' => 'h:i A', 'js' => 'hh:mm A'];
        DB::table('configurable')->insert(
            array(
                array(
                    'group' => 'date_format',
                    'name' => 'date',
                    'value' => serialize($date_format),
                    'configurable_id' => $account_id,
                    'created_by' => $user_id
                ),
                array(
                    'group' => 'date_format',
                    'name' => 'date',
                    'value' => serialize($time_format),
                    'configurable_id' => $account_id,
                    'created_by' => $user_id
                )
            )
        );
        //APPOINTMENT STATUS Settings
        DB::table('appointment_statuses')->insert(
            array(
                array(
                    'name' => 'Confirmed',
                    'background_color' => '#5cb85c',
                    'text_color' => 'white',
                    'account_id' => $account_id,
                    'required' => true,
                    'is_active' => true,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Declined',
                    'background_color' => '#d9534f',
                    'text_color' => 'white',
                    'account_id' => $account_id,
                    'required' => true,
                    'is_active' => true,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Pending',
                    'background_color' => '#8c8e9a',
                    'text_color' => 'white',
                    'account_id' => $account_id,
                    'required' => true,
                    'is_active' => true,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Re-Schedule',
                    'background_color' => '#f78f01',
                    'text_color' => 'white',
                    'account_id' => $account_id,
                    'required' => true,
                    'is_active' => true,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                )
            )
        );
        //ORDER STATUSES Settings
        DB::table('order_statuses')->insert(
            array(
                array(
                    'name' => 'New Order',
                    'background_color' => '#0db7dd',
                    'text_color' => 'white',
                    'account_id' => $account_id,
                    'required' => true,
                    'is_active' => true,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Completed',
                    'background_color' => '#5cb85c',
                    'text_color' => 'white',
                    'account_id' => $account_id,
                    'required' => true,
                    'is_active' => true,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                )
            )
        );
        //QUOTE STATUSES Settings
        DB::table('quote_statuses')->insert(
            array(
                'name' => 'Good',
                'background_color' => '#358ca7',
                'text_color' => '#fff',
                'account_id' => $account_id,
                'immutable' => 0,
                'is_active' => 1,
                'created_by' => $user_id,
                'updated_by' => $user_id
            )
        );
        $status_id = DB::table('quote_statuses')->insertGetId(
            array(
                'name' => 'Lost',
                'background_color' => '#d80000',
                'text_color' => '#fff',
                'account_id' => $account_id,
                'immutable' => 0,
                'is_active' => 1,
                'created_by' => $user_id,
                'updated_by' => $user_id
            )
        );
        DB::table('quote_statuses')->insert(
            array(
                array(
                    'name' => 'Hot Lead',
                    'background_color' => '#10f603',
                    'text_color' => '#050505',
                    'account_id' => $account_id,
                    'immutable' => 0,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Cold Lead',
                    'background_color' => '#0307f6',
                    'text_color' => '#fdf9f9',
                    'account_id' => $account_id,
                    'immutable' => 0,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                )
            ,
                array(
                    'name' => 'Follow up',
                    'background_color' => '#f603eb',
                    'text_color' => '#fdf9f9',
                    'account_id' => $account_id,
                    'immutable' => 0,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                )
            )
        );
        //QUOTE STATUSES Reasons
        DB::table('quote_status_reasons')->insert(
            array(
                array(
                    'reason' => 'Too Expensive',
                    'background_color' => '#f60505',
                    'text_color' => '#FFF',
                    'quote_status_id' => $status_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'reason' => 'Backed Out',
                    'background_color' => '#f0303',
                    'text_color' => '#FFF',
                    'quote_status_id' => $status_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'reason' => 'Bought Elsewhere',
                    'background_color' => '#f20606',
                    'text_color' => '#FFF',
                    'quote_status_id' => $status_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'reason' => 'Lack of Product Range',
                    'background_color' => '#f20606',
                    'text_color' => '#FFF',
                    'quote_status_id' => $status_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'reason' => 'Lead Time Issue',
                    'background_color' => '#f20606',
                    'text_color' => '#FFF',
                    'quote_status_id' => $status_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'reason' => 'No Show at Appointment',
                    'background_color' => '#f20606',
                    'text_color' => '#FFF',
                    'quote_status_id' => $status_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'reason' => 'No Answering calls',
                    'background_color' => '#f20606',
                    'text_color' => '#FFF',
                    'quote_status_id' => $status_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                )
            )
        );
        //TIERS Settings
        DB::table('tiers')->insert(
            array(
                array(
                    'name' => 'Default',
                    'account_id' => $account_id,
                    'required' => 1,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                )
            )
        );
        //Product Document Category
        DB::table('product_document_categories')->insert(
            array(
                array(
                    'category' => 'Order',
                    'account_id' => $account_id,
                    'required' => 1,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'category' => 'Install',
                    'account_id' => $account_id,
                    'required' => 1,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'category' => 'Measure',
                    'account_id' => $account_id,
                    'required' => 1,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'category' => 'General',
                    'account_id' => $account_id,
                    'required' => 1,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                )
            )
        );
        //
        //Windows
        DB::table('windows')->insert(
            array(
                array(
                    'name' => 'Dining',
                    'account_id' => $account_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Bedroom',
                    'account_id' => $account_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Kitchen',
                    'account_id' => $account_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Living',
                    'account_id' => $account_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Rooftop',
                    'account_id' => $account_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Bathroom',
                    'account_id' => $account_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Ensuite',
                    'account_id' => $account_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Entry',
                    'account_id' => $account_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Family',
                    'account_id' => $account_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ),
                array(
                    'name' => 'Games',
                    'account_id' => $account_id,
                    'is_active' => 1,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                )
            )
        );
        $this->info('Default Dealer Setting End.');
    }
}
