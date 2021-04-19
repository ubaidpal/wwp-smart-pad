<?php
/**
 * Created by PhpStorm.
 * User: Nabeel Qadri
 * Date: 6/14/2019
 * Time: 4:52 PM
 */

namespace App\Repository\admin\Criteria;

abstract class PaymentGateway
{

    public $type;
    abstract function processPayment($amount, $params = []);


    abstract function refund($txn_id, $amount);


}
