<?php
/**
 * Created by   :  Ubaid UllAh
 * Project Name : SmartPad
 * Product Name : PhpStorm
 * Date         : 04/8/2019 2:32 PM
 * File Name    : ProductOptionFacade.php
 */

namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class ProductOptionFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'productoption';
	}
}
