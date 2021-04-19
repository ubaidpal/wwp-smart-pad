<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DbUpdateForNewPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $result = DB::select(DB::raw('SELECT distinct(employee_id), account_id, role_id FROM permissions'));
        
        foreach($result as $employee) {
            if($employee->employee_id) {
                foreach(['workflow-manager.all-staffs', 'measure-and-install.all-staffs', 'quotes.all-staffs', 'scheduler.all-staffs'] as $permission)
                {
                    DB::statement("DELETE FROM permissions WHERE employee_id = ".$employee->employee_id." AND permission = '".$permission."'");
                    DB::statement("
                        INSERT INTO permissions(role_id, employee_id, permission, account_id)
                        VALUES(".($employee->role_id ? $employee->role_id : "NULL").", '".$employee->employee_id."', '".$permission."', '".$employee->account_id."');
                    ");
                }
            }
        }
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
