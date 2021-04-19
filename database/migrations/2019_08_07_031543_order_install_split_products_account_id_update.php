<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderInstallSplitProductsAccountIdUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $result = DB::select(DB::raw('SELECT * FROM order_install_splits'));
        
        foreach($result as $split) {
            $products = DB::select(DB::raw("SELECT * FROM order_install_split_products where order_install_split_id=".$split->id));

            if(count($products) > 0) {
                DB::table('order_install_split_products')->where('order_install_split_id', $split->id)
                ->update([
                    'account_id' => $split->account_id
                ]);
            }
        }

        return 'success';
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
