<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrdersUpdateTierIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $accounts = DB::table('accounts')->get();
        foreach($accounts as $account) {
            $defaultTier = DB::table('tiers')->where('account_id', $account->id)->where('required', 1)->first();

            if($defaultTier) {
                DB::statement(DB::raw("
                    UPDATE orders SET tier_id = $defaultTier->id WHERE account_id = $account->id;
                "));
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
