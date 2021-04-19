<?php
/**
 * Created by   :  Ubaid UllAh
 * Project Name : SmartPad
 * Product Name : PhpStorm
 * Date         : 05/28/2019 2:32 PM
 * File Name    : ProductOptionJsonFacade.php
 */

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class ProductOptionJsonFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'productoptionjson';
	}
}
