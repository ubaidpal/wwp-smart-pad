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

class ZippayRepository extends PaymentGateway
{
    private $apiKey      = '';
    private $apiPassword = '';
    private $apiEndPoint = '';

    public function __construct() {
        $this->type = 'eway';
    }

    /*
     * Set Authentication Paramters for the API
     * @param: Api Key
     * @param: Password
     * @param: Sandbox
     * */
    function setAuth($apiKey, $password, $sandbox = FALSE) {
        $this->apiKey      = $apiKey;
        $this->apiPassword = $password;
        $this->setSandbox($sandbox);
    }

    /*
     * Test Connection by a test sandbox transaction
     * */
    public function testConnection() {
        $apiEndpoint = \Eway\Rapid\Client::MODE_SANDBOX;
        $client      = \Eway\Rapid::createClient($this->apiKey, $this->apiPassword, $apiEndpoint);
        $transaction = [
            'Customer'        => [
                'CardDetails' => [
                    'Name'        => 'John Smith',
                    'Number'      => '4444333322221111',
                    'ExpiryMonth' => '12',
                    'ExpiryYear'  => '25',
                    'CVN'         => '123',
                ]
            ],
            'Payment'         => [
                'TotalAmount' => 1000,
            ],
            'TransactionType' => \Eway\Rapid\Enum\TransactionType::PURCHASE,
        ];

        $response = $client->createTransaction(\Eway\Rapid\Enum\ApiMethod::DIRECT, $transaction);

        if(isset($response->TransactionStatus)) {
            return [
                'success' => TRUE,
                'message' => 'Payment successful! ID: ' . $response->TransactionID
            ];
        } else {
            return [
                'success' => FALSE,
                'message' => 'Transaction Failed'
            ];
        }
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
    public function processPayment($amount, $params=[]) {
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
                'message' => $validator->errors()->first()
            ];
        }else{
            $validated = true;
        }

        if($validated) {
            $expiryMonth = $params[ 'expiryMonth' ];
            $expiryYear  = $params[ 'expiryYear' ];
            $number      = $params[ 'cc' ];
            $cvn         = $params[ 'cvn' ];

            $client     = \Eway\Rapid::createClient($this->apiKey, $this->apiPassword, $this->apiEndPoint);
            $expiryDate = $expiryMonth . '/' . $expiryYear;
            $validate   = $this->validate($number, $expiryDate, $cvn, $amount);
            if($validate) {
                $transaction = [
                    'Customer'        => [
                        'CardDetails' => [
                            'Name'        => "$name",
                            'Number'      => "$number",
                            'ExpiryMonth' => "$expiryMonth",
                            'ExpiryYear'  => "$expiryYear",
                            'CVN'         => "$cvn",
                        ]
                    ],
                    'Payment'         => [
                        'TotalAmount' => $amount * 100,
                    ],
                    'TransactionType' => \Eway\Rapid\Enum\TransactionType::PURCHASE,
                ];

                $response = $client->createTransaction(\Eway\Rapid\Enum\ApiMethod::DIRECT, $transaction);
                if(!empty($response->getErrors())) {
                    foreach ($response->getErrors() as $error) {
                        $error_messages[] = \Eway\Rapid::getMessage($error);
                    }
                    return [
                        'success'   => FALSE,
                        'type'      => $this->type,
                        'error_msg' => $error_messages
                    ];
                }
                if(isset($response->TransactionStatus)) {
                    return [
                        'success'        => TRUE,
                        'transaction_id' => $response->TransactionID,
                        'type'           => $this->type,
                        'txn_meta'       => $response,
                    ];
                } else {
                    return [
                        'success'   => FALSE,
                        'type'      => $this->type,
                        'error_msg' => 'Transaction is not successful.'
                    ];
                }
            } else {
                return [
                    'success'   => FALSE,
                    'type'      => $this->type,
                    'error_msg' => 'Invalid Card Information'
                ];

            }
        }
    }

    /*
     * Set Testing Environment
     * @param: $sandbox true or false
     * */
    public function setSandbox($sandbox = FALSE) {
        if($sandbox) {
            $this->endPoint = \Eway\Rapid\Client::MODE_SANDBOX;
        } else {
            $this->endPoint = \Eway\Rapid\Client::MODE_PRODUCTION;
        }
    }

    /*
     * Refund a transaction
     * @param: Transaction Id
     * @param: amount to refund
     * */
    public function refund($txn_id, $amount) {
        $client = \Eway\Rapid::createClient($this->apiKey, $this->apiPassword, $this->apiEndPoint);

        $refund = [
            'Refund' => [
                'TransactionID' => $txn_id,
                'TotalAmount'   => $amount
            ],
        ];

        $response = $client->refund($refund);
        return $response;
    }

    /*
     * Check Transaction Status by transaction Id
     * @param: transaction id
     * */
    public function checkTransactionStatus($txn_id) {
        $client = \Eway\Rapid::createClient($this->apiKey, $this->apiPassword, $this->apiEndPoint);

        $response = $client->queryTransaction($txn_id);

        // See the JSON tab for all the available properties

        $response = $response->Transactions[ 0 ];

        if(isset($response->TransactionStatus)) {
            return [
                'success' => TRUE,
                'status'  => $response->TransactionStatus
            ];
        } else {
            $errors        = split(', ', $response->ResponseMessage);
            $error_message = '';
            foreach ($errors as $error) {
                $error_message .= "Payment failed: " . \Eway\Rapid::getMessage($error) . "<br>";
            }
            return [
                'success' => FALSE,
                'error'   => $error_message
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
