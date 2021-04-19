<?php
/**
 * Created by   :  Haroon Zuberi
 * Project Name : wwp2
 * Product Name : PhpStorm
 * Date         : 6/18/19 2:19 PM
 * File Name    : EwayRepository.php
 */

namespace App\Repository\admin;

use App\Repository\admin\Criteria\PaymentGateway;
use Illuminate\Support\Facades\Validator;

require base_path() . '/vendor/autoload.php';

class BraintreeRepository extends PaymentGateway
{
    public function __construct() {
        $this->type = 'braintree';
    }

    public $braintree_environment;
    public $braintree_merchant_id;
    public $braintree_public_key;
    public $braintree_private_key;
    public $gateway;
    /*
     * Set Authentication Paramters for the API
     * @param: Api Key
     * @param: Password
     * @param: Sandbox
     * */
    function setAuth($braintree_environment, $braintree_merchant_id , $braintree_public_key, $braintree_private_key) {
        $this->braintree_environment = $braintree_environment;
        $this->braintree_merchant_id = $braintree_merchant_id;
        $this->braintree_public_key = $braintree_public_key;
        $this->braintree_private_key = $braintree_private_key;

        $config = new Braintree\Configuration([
            'environment' => $this->braintree_environment,
            'merchantId' => $this->braintree_merchant_id,
            'publicKey' => $this->braintree_public_key,
            'privateKey' => $this->braintree_private_key
        ]);
        $this->gateway = new Braintree\Gateway($config);
    }


    /*
     * Process Payment
     * @param: amount
     * @param: name
     * @param: number
     * @param: expiry Month
     * @param: expiry year
     * @param: cvv number
     * */
    public function processPayment($amount, $params = []) {
        $rules = [
            'client_id' => 'required',
        ];
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $validator = Validator::make($params, $rules, $customMessages);

        if ( $validator->fails() )
        {
            return [
                'success' => 0,
                'message' => implode(' ',$validator->errors()->all())
            ];
        }else{
            $validated = true;
        }
        if($validated){
            $client_id = $params['client_id'];
            $response = $this->gateway->transaction()->sale([
                'amount' => $amount,
                'merchantAccountId' => 'wwpdev',
                'paymentMethodNonce' => $client_id,
                'options' => [
                    'submitForSettlement' => True
                ]
            ]);
            if(isset($response['status']) && $response['status'] == 'succeeded'){
                return [
                    'success' => true,
                    'type' => $this->type,
                    'txn_id' => $response['id'],
                    'txn_meta' => $response,
                    'message' => ''

                ];
            }else{
                return [
                    'success' => false,
                    'type' => $this->type,
                    'message' => 'Invalid Token',
                    'txn_meta' => $response
                ];
            }


        }


    }

    /*
    * Refund a transaction
    * @param: Transaction Id
    * @param: amount to refund
    * */
    public function refund($txn_id, $amount=null) {
        $re = $this->gateway->transaction()->refund($txn_id,$amount);
        if(isset($re->success)){
            return [
                'success' => true,
                'type' => $this->type,
                'refund_meta' => $re,
                'error' => ''

            ];
        }else{
            return [
                'success' => false,
                'type' => $this->type,
                'refund_meta' => $re,
                'error' => 'Invalid Data'
            ];
        }
    }
}
