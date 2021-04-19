<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->insert([
            'id' => 1,
            'name' => 'Brand for Testing',
            'type' => 1,
            'country_id' => 14,
            'price_type' => 'retail_including'
        ]);

        // DB::table('brands')->insert([
        //     'id' => 2,
        //     'name' => 'Brand Y',
        //     'type' => 1,
        //     'country_id' => 14,
        //     'price_type' => 'retail_including'
        // ]);

        // DB::table('brands')->insert([
        //     'id' => 3,
        //     'name' => 'Brokrr Shards',
        //     'type' => 1,
        //     'country_id' => 14,
        //     'price_type' => 'retail_including'
        // ]);

        DB::table('categories')->insert([
            'id' => 1,
            'name' => 'Category for Testing'
        ]);

        // DB::table('categories')->insert([
        //     'id' => 2,
        //     'name' => 'Weaponry'
        // ]);

        // DB::table('categories')->insert([
        //     'id' => 3,
        //     'name' => 'Armoury'
        // ]);

        // DB::table('tiers')->insert([
        //     'id' => 1,
        //     'name' => 'Default',
        //     'account_id' => 2,
        //     'required' => 1,
        //     'created_by' => 1,
        //     'updated_by' => 1
        // ]);

        DB::table('products')->insert([
            'id' => 1,
            'name' => 'Product for Testing',
            'brand_id' => 1,
            'category_id' => 1,
            'country_id' => 14
        ]);

        // DB::table('products')->insert([
        //     'id' => 2,
        //     'name' => 'Product 2',
        //     'brand_id' => 1,
        //     'category_id' => 1,
        //     'country_id' => 14
        // ]);

        // DB::table('products')->insert([
        //     'id' => 3,
        //     'name' => 'Swords',
        //     'brand_id' => 3,
        //     'category_id' => 2,
        //     'country_id' => 14,
        //     'options' => '[{"id":33,"type":"option","secret":"","price":"","sub_options":[{"id":25,"parent_id":33,"top_parent_id":33,"sub_option_name":"Long Sword","price_structure":"free","quantity_status":"1","apply_discount":"1","price_percent":"0","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":26,"parent_id":33,"top_parent_id":33,"sub_option_name":"Broad Sword","price_structure":"flat_price","quantity_status":"1","apply_discount":"1","price_percent":"150","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":27,"parent_id":33,"top_parent_id":33,"sub_option_name":"Rapier","price_structure":"percent_added_to_base","quantity_status":"1","apply_discount":"1","price_percent":"10","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":28,"parent_id":33,"top_parent_id":33,"sub_option_name":"Flameberge","price_structure":"per_metre_width","quantity_status":"1","apply_discount":"1","price_percent":"250","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":29,"parent_id":33,"top_parent_id":33,"sub_option_name":"Claymore","price_structure":"per_metre_height","quantity_status":"1","apply_discount":"1","price_percent":"250","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":30,"parent_id":33,"top_parent_id":33,"sub_option_name":"Saber","price_structure":"width_based_chart","quantity_status":"1","apply_discount":"1","price_percent":"125","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[],"price_grid":"[{\"width\":\"900\",\"price\":\"900\"},{\"width\":\"1000\",\"price\":\"1000\"},{\"width\":\"1100\",\"price\":\"1100\"},{\"width\":\"1200\",\"price\":\"1200\"},{\"width\":\"1300\",\"price\":\"1300\"}]"},{"id":31,"parent_id":33,"top_parent_id":33,"sub_option_name":"Cutlass","price_structure":"height_based_chart","quantity_status":"1","apply_discount":"1","price_percent":"125","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[],"price_grid":"[{\"height\":\"900\",\"price\":\"900\"},{\"height\":\"1000\",\"price\":\"1000\"},{\"height\":\"1100\",\"price\":\"1100\"},{\"height\":\"1200\",\"price\":\"1200\"},{\"height\":\"1300\",\"price\":\"1300\"}]"},{"id":32,"parent_id":33,"top_parent_id":33,"sub_option_name":"Great Sword","price_structure":"grid_option_chart","quantity_status":"1","apply_discount":"1","price_percent":"125","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":33,"parent_id":33,"top_parent_id":33,"sub_option_name":"Zweihander","price_structure":"add_price_per_sm","quantity_status":"1","apply_discount":"1","price_percent":"125","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":34,"parent_id":33,"top_parent_id":33,"sub_option_name":"Katana","price_structure":"percent_added_to_option","quantity_status":"1","apply_discount":"1","price_percent":"15","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[],"linked_option_id":36}],"constraints":[],"linked_sub_count":0,"is_selected":false,"options_name":"Sword Type"},{"id":34,"type":"text","secret":"","price":"","sub_options":[],"constraints":[],"linked_sub_count":0,"is_selected":false,"options_name":"Extras","linked_options":[]},{"id":35,"type":"multi","secret":"","price":"","sub_options":[{"id":35,"parent_id":35,"top_parent_id":35,"sub_option_name":"Ruby","price_structure":"flat_price","quantity_status":0,"apply_discount":0,"price_percent":"150","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":36,"parent_id":35,"top_parent_id":35,"sub_option_name":"Sapphire","price_structure":"flat_price","quantity_status":0,"apply_discount":0,"price_percent":"250","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":37,"parent_id":35,"top_parent_id":35,"sub_option_name":"DIAMOND","price_structure":"percent_added_to_option","quantity_status":0,"apply_discount":0,"price_percent":"100","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[],"linked_option_id":36}],"constraints":[],"linked_sub_count":0,"is_selected":false,"options_name":"Jewels"},{"id":36,"type":"option","secret":"","price":"","sub_options":[{"id":38,"parent_id":36,"top_parent_id":36,"sub_option_name":"Low","price_structure":"flat_price","quantity_status":"1","apply_discount":0,"price_percent":"100","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":39,"parent_id":36,"top_parent_id":36,"sub_option_name":"Medium","price_structure":"flat_price","quantity_status":"1","apply_discount":0,"price_percent":"500","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]},{"id":40,"parent_id":36,"top_parent_id":36,"sub_option_name":"High","price_structure":"flat_price","quantity_status":"1","apply_discount":0,"price_percent":"1000","price_type":0,"mark_default":0,"is_selected":false,"sub_options":[]}],"constraints":[],"linked_sub_count":2,"is_selected":false,"options_name":"Sword Quality","linked_options":[33,35]}]'
        // ]);

        // DB::table('items')->insert([
        //     'id' => 1,
        //     'name' => 'Item 1',
        //     'price' => 100,
        //     'category_id' => 1,
        //     'brand_id' => 1
        // ]);

        // DB::table('items')->insert([
        //     'id' => 2,
        //     'name' => 'Item 2',
        //     'price' => 100,
        //     'category_id' => 1,
        //     'brand_id' => 1
        // ]);
    }
}
