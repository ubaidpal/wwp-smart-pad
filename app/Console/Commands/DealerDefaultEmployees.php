<?php

namespace App\Console\Commands;

use App\Models\Accounts;
use App\Models\Employees;
use App\Models\EmployeesRoles;
use App\Models\Role;
use Illuminate\Console\Command;

class DealerDefaultEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dealer:defaultEmployees {Id}';

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
        $this->info('Default Dealer Employees Start.');
        $account_id = $this->argument('Id');
        $acct = Accounts::find($account_id);
        //
        $employee = new Employees();
        $employee->status           = 'active';
        $employee->password         = bcrypt('password123');
        $employee->username         = $acct->email;
        $employee->first_name       = $acct->first_name;
        $employee->last_name        = $acct->last_name;
        $employee->email            = $acct->email;;
        $employee->text_color       = '#FFFFFF';
        $employee->background_color = '#000000';
        $employee->account_id       = $account_id;
        $employee->save();

        //
        $role = Role::where('account_id',$account_id)->first();
        $newEmployeeRoles              = new EmployeesRoles();
        $newEmployeeRoles->role_id     = $role->id;
        $newEmployeeRoles->employee_id = $employee->id;
        $newEmployeeRoles->account_id = $account_id;
        $newEmployeeRoles->save();
        //

        $employee1                  = new Employees();
        $employee1->status           = 'active';
        $employee1->is_appointee     = 0;
        $employee1->first_name       = 'Smartpad Pro';
        $employee1->last_name        = 'Admin';
        $employee1->email            = $acct->username.'@smartpadpro.com.au';
        $employee1->password         = bcrypt('Smartpadpro12!');
        $employee1->text_color       = '#FFFFFF';
        $employee1->background_color = '#000000';
        $employee1->username         = $acct->username;
        $employee1->maximum_discount   = 200;
        $employee1->main_admin_acceess = true;
        $employee1->account_id       = $account_id;
        $employee1->save();

        $newEmployeeRoles2              = new EmployeesRoles();
        $newEmployeeRoles2->role_id     = $role->id;
        $newEmployeeRoles2->employee_id = $employee1->id;
        $newEmployeeRoles2->account_id = $account_id;
        $newEmployeeRoles2->save();

        $this->info('Default Dealer Employees End.');
    }
}
