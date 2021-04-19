<?php

namespace App\Observers;

use App\Models\Employees;

class EmployeesObserver
{

    /**
     * Handle the accounts "creating" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function creating(Employees $employee)
    {
        //
        //$employee->username = str_slug($employee->username);
    }
    /**
     * Handle the accounts "created" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function created(Employees $employee)
    {
        //
    }

    /**
     * Handle the accounts "updated" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function updated(Employees $employee)
    {
        //
    }

    /**
     * Handle the accounts "updating" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function updating(Employees $employee)
    {
        //
    }
    /**
     * Handle the accounts "deleted" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function deleted(Employees $employee)
    {
        //
    }
    /**
     * Handle the accounts "deleting" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function deleting(Employees $employee)
    {
        //
    }

    /**
     * Handle the accounts "restored" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function restored(Employees $employee)
    {
        //
    }

    /**
     * Handle the accounts "force deleted" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function forceDeleted(Employees $employee)
    {
        //
    }
    /**
     * Handle the accounts "restoring" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function restoring(Employees $employee)
    {
        //
    }


    /**
     * Handle the accounts "saving" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function saving(Employees $employee)
    {
        //
    }

    /**
     * Handle the accounts "saved" event.
     *
     * @param  \App\Models\Employees;  $employee
     * @return void
     */
    public function saved(Employees $employee)
    {
        //
    }




}
