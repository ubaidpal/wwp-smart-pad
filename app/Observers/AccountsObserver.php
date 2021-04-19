<?php

namespace App\Observers;

use App\Models\Accounts;

class AccountsObserver
{

    /**
     * Handle the accounts "creating" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function creating(Accounts $accounts)
    {
        //
        $accounts->status =  ucfirst($accounts->status);
    }
    /**
     * Handle the accounts "created" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function created(Accounts $accounts)
    {
        //
    }

    /**
     * Handle the accounts "updated" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function updated(Accounts $accounts)
    {
        //
    }

    /**
     * Handle the accounts "updating" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function updating(Accounts $accounts)
    {
        //
    }
    /**
     * Handle the accounts "deleted" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function deleted(Accounts $accounts)
    {
        //
    }
    /**
     * Handle the accounts "deleting" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function deleting(Accounts $accounts)
    {
        //
    }

    /**
     * Handle the accounts "restored" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function restored(Accounts $accounts)
    {
        //
    }

    /**
     * Handle the accounts "force deleted" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function forceDeleted(Accounts $accounts)
    {
        //
    }
    /**
     * Handle the accounts "restoring" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function restoring(Accounts $accounts)
    {
        //
    }


    /**
     * Handle the accounts "saving" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function saving(Accounts $accounts)
    {
        //
    }

    /**
     * Handle the accounts "saved" event.
     *
     * @param  \App\Models\Accounts  $accounts
     * @return void
     */
    public function saved(Accounts $accounts)
    {
        //
    }




}
