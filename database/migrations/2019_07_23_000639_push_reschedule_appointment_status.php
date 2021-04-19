<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PushRescheduleAppointmentStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $appointment_statuses = DB::table('accounts')
                        ->select('appointment_statuses.name', 'appointment_statuses.account_id')
                        ->leftJoin('appointment_statuses', 'appointment_statuses.account_id', 'accounts.id')
                        ->get();
        
        $appointment_status = [];
        foreach($appointment_statuses as $appointment)
        {
            $appointment_status[$appointment->account_id][$appointment->name] = $appointment->name;
        }
        
        $insert = [];
        foreach($appointment_status as $account_id => $appointment)
        {
            if($account_id > 0)
            {
                if(!in_array('Re-Schedule', $appointment))
                {
                    $insert[] = [
                                'name' => 'Re-Schedule',
                                'background_color' => '#ffe62b',
                                'text_color' => '#000',
                                'account_id' => $account_id,
                                'required' => true,
                                'is_active' => true
                            ];
                }  
            }
        }

        DB::table('appointment_statuses')->insert($insert);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
