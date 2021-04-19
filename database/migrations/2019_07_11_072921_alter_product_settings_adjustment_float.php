<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductSettingsAdjustmentFloat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('alter table product_settings modify column discount_received float;');
        DB::statement('alter table product_settings modify column base_price float;');
        DB::statement('alter table product_settings modify column cost float;');
        DB::statement('alter table product_settings modify column sell_for float;');
        DB::statement('alter table product_settings_tiers modify column price_adjustment_product float;');
        DB::statement('alter table product_settings_tiers modify column price_adjustment_options float;');

        DB::statement('alter table item_settings modify column discount_received float;');
        DB::statement('alter table item_settings modify column price_adjustment_product float;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('alter table product_settings modify column discount_received int;');
        DB::statement('alter table product_settings modify column base_price int;');
        DB::statement('alter table product_settings modify column cost int;');
        DB::statement('alter table product_settings modify column sell_for int;');
        DB::statement('alter table product_settings_tiers modify column price_adjustment_product int;');
        DB::statement('alter table product_settings_tiers modify column price_adjustment_options int;');

        DB::statement('alter table item_settings modify column discount_received int;');
        DB::statement('alter table item_settings_tiers modify column price_adjustment_product int;');
    }
}
