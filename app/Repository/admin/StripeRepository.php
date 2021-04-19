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

class StripeRepository extends PaymentGateway
{
    private $apiKey      = '';

    public function __construct() {
    }

    /*
     * Set Authentication Paramters for the API
     * @param: Api Key
     * @param: Password
     * @param: Sandbox
     * */
    function setAuth($apiKey) {
        $this->apiKey      = $apiKey;
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
            'expiryMonth' => 'required',
            'expiryYear' => 'required',
            'cc' => 'required',
            'cvn' => 'required',
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
            $expiryMonth = $params[ 'expiryMonth' ];
            $expiryYear  = $params[ 'expiryYear' ];
            $number      = $params[ 'cc' ];
            $cvn         = $params[ 'cvn' ];

            $expiryDate = $expiryMonth . '/' . $expiryYear;


            $validate   = $this->validate($number, $expiryDate, $cvn, $amount);
           // echo '<pre>'; print_r('validated'); echo '</pre>';die;
            if($validate) {
                \Stripe\Stripe::setApiKey($this->apiKey);
                $token = \Stripe\Token::create([
                    'card' => [
                        'number'    => $number,
                        'exp_month' => $expiryMonth,
                        'exp_year'  => $expiryYear,
                        'cvc'       => $cvn
                    ]
                ]);
                if(!isset($token['id'])){
                    return [
                        'success' => FALSE,
                        'type'    => 'stripe',
                        'message' => 'Invalid Card Information'
                    ];
                }
                $charge = \Stripe\Charge::create([
                    "amount"      => $amount,
                    "currency"    => "usd",
                    "source"      => ['id'], // obtained with Stripe.js
                    "description" => "Charge for " . $name
                ]);
                if(isset($charge[ 'status' ]) && $charge[ 'status' ] == 'succeeded') {
                    return [
                        'success'  => TRUE,
                        'type'     => 'stripe',
                        'txn_id'   => $charge[ 'id' ],
                        'txn_meta' => $charge,
                        'message'  => ''

                    ];
                } else {
                    return [
                        'success'  => FALSE,
                        'type'     => 'stripe',
                        'message'  => 'Invalid Token',
                        'txn_meta' => $charge
                    ];
                }

            } else {
                return ['success' => FALSE, 'error_msg' => 'Invalid Card Information'];

            }
        }


    }

    /*
    * Refund a transaction
    * @param: Transaction Id
    * @param: amount to refund
    * */
    public function refund($txn_id, $amount = NULL) {
        \Stripe\Stripe::setApiKey($this->apiKey);
        $refund = [
            "charge" => $txn_id,
        ];
        if($amount > 0) {
            $refund[ 'amount' ] = $amount;
        }
        $re = \Stripe\Refund::create($refund);
        if(isset($re[ 'status' ]) && $re[ 'status' ] == 'succeeded') {
            return [
                'success'     => TRUE,
                'type'        => 'stripe',
                'refund_id'   => $re[ 'id' ],
                'refund_meta' => $re,
                'error'       => ''

            ];
        } else {
            return [
                'success'     => FALSE,
                'type'        => 'stripe',
                'refund_id'   => $re[ 'id' ],
                'refund_meta' => $re,
                'error'       => 'Invalid Data'

            ];
        }
    }



    /**
     * Global validation test that checks all other child validation tests
     * @see valid*
     * @return bool FALSE if ANY validation test fails, TRUE if all pass successfully
     */
    public function validate($cc, $expiryDate, $cvv, $amount) {
        return (
            $this->validCC($cc)
            && $this->validExpiryDate($expiryDate)
            && $this->validCvv($cvv)
            && $this->validChargeAmount($amount)
        );
    }

    /**
     * validates Credit Card number and returns a bool indicating its validity
     *
     * @param string|int $Cc Optional credit card number to validate. If none is specified the objects Credit Card number is used instead
     *
     * @return bool TRUE if the CC number passes validation
     */
    public function validCc($Cc = NULL) {
        $test_cc = ($Cc) ? $Cc : '';
        if(preg_match('/[0-9]{12,16}/', $test_cc) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * validates an expiry date and ensures that it confirms to the SecurePay standards
     *
     * @param string $ExpiryDate Optional expiry date to test. If none is specified the objects expiry date is used instead
     *
     * @return bool TRUE if the expiry date passes validation
     */
    public function validExpiryDate($ExpiryDate = NULL) {
        $test_expiry = ($ExpiryDate) ? $ExpiryDate : '';
        if(preg_match('!([0-9]{1,2})/([0-9]{2,4})!', $test_expiry, $matches)) {
            if(strlen($matches[ 1 ]) == 1)
                $matches[ 1 ] = "0{$matches[1]}";
            if(strlen($matches[ 2 ]) == 4)
                $matches[ 2 ] = substr($matches[ 2 ], -2);
            $this->ExpiryDate = "{$matches[1]}/{$matches[2]}";

            return (($matches[ 1 ] > 0) && ($matches[ 1 ] < 13) && ($matches[ 2 ] >= date('y')) && ($matches[ 2 ] < date('y') + 30)); // Check that month and years are valid
        } else {
            return FALSE; // Failed RegExp checks
        }
    }

    /**
     * validates a CVV code and ensures that it confirms to the SecurePay standards
     *
     * @param string|int $CVV  Optional CVV to test. If none is specified the objects CVV is used instead
     * @param bool $ForceValue Optional value indicating that the Cvv HAS to contain a value
     *
     * @return bool TRUE if the CVV passes validation
     */
    public function validCvv($Cvv = NULL) {
        $test_cvv = ($Cvv) ? $Cvv : '';
        if($test_cvv) {
            return (preg_match('/[0-9]{3,4}/', $test_cvv, $matches));
        } else // Does not have to contain and value and doesn't
            return TRUE;
    }

    /**
     * validates a Charge Amount and ensures that it confirms to the SecurePay standards
     *
     * @param float $Amount Optional Charge Amount to test. If none is specified the objects Charge Amount is used instead
     *
     * @return bool TRUE if the Charge Amount passes validation
     */
    public function validChargeAmount($ChargeAmount = NULL) {
        $test_amount = ($ChargeAmount) ? $ChargeAmount : '';
        if($test_amount > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
