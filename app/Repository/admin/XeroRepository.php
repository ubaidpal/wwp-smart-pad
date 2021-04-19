<?php
/**
 * Created by PhpStorm.
 * User: Nabeel Qadri
 * Date: 7/11/2019
 * Time: 1:17 PM
 */

use DrawMyAttention\XeroLaravel\Facades;

class XeroRepository extends Repository
{
    public function setAuth($params = []) {

    }

    public function CreateDraftInvoice($params = []) {
        $rules          = [
            'account_number' => 'required',
            'contact_status' => 'required',
            'name'           => 'required',
            'first_name'     => 'required',
            'last_name'      => 'required',
            'email'          => 'required',
            'currency'       => 'required',
            'type'           => 'required',
            'invoice_number' => 'required',
            'reference'      => 'required',
            'currency_code'  => 'required',
            'status'         => 'required',
            'quantity'       => 'required',
            'unit_amount'    => 'required',
            'tax_amount'     => 'required',
            'line_amount'    => 'required',
            'discount_rate'  => 'required',
        ];
        $customMessages = [
            'required' => 'The :attribute field is required.'
        ];
        $validator      = Validator::make($params, $rules, $customMessages);

        if($validator->fails()) {
            return [
                'success' => 0,
                'message' => implode(' ', $validator->errors()->all())
            ];
        } else {
            $validated = TRUE;
        }
        if($validated) {
            $account_number = $params[ 'account_number' ];
            $contact_status = $params[ 'contact_status' ];
            $name           = $params[ 'name' ];
            $first_name     = $params[ 'first_name' ];
            $last_name      = $params[ 'last_name' ];
            $email          = $params[ 'email' ];
            $currency       = $params[ 'currency' ];
            $invoice_number = $params[ 'invoice_number' ];
            $reference      = $params[ 'reference' ];
            $currency_code  = $params[ 'currency_code' ];
            $status         = $params[ 'status' ];
            $quantity       = $params[ 'quantity' ];
            $unit_amount    = $params[ 'unit_amount' ];
            $tax_amount     = $params[ 'tax_amount' ];
            $line_amount    = $params[ 'line_amount' ];
            $discount_rate  = $params[ 'discount_rate' ];

            $xero    = new XeroPHP\Application\PrivateApplication();
            $invoice = new XeroPHP\Models\Accounting\Invoice();
            $contact = new XeroPHP\Models\Accounting\Contact\ContactPerson();

            // Set up the invoice contact
            $contact->setAccountNumber($account_number);
            $contact->setContactStatus($contact_status);
            $contact->setName($name);
            $contact->setFirstName($first_name);
            $contact->setLastName($last_name);
            $contact->setEmailAddress($email);
            $contact->setDefaultCurrency($currency);

            // Assign the contact to the invoice
            $invoice->setContact($contact);

            // Set the type of invoice being created (ACCREC / ACCPAY)
            $invoice->setType('ACCREC');

            $dateInstance = new DateTime();
            $invoice->setDate($dateInstance);
            $invoice->setDueDate($dateInstance);
            $invoice->setInvoiceNumber($invoice_number);
            $invoice->setReference($reference);

            // Provide an URL which can be linked to from Xero to view the full order
//            $invoice->setUrl('http://yourdomain/fullpathtotheorder');

            $invoice->setCurrencyCode($currency_code);
            $invoice->setStatus($status);

            // Create some order lines
            $line1 = new XeroPHP\Models\Accounting\Invoice\LineItem();
            $line1->setDescription('Window furnishings as per order '.$invoice_number);

            $line1->setQuantity($quantity);
            $line1->setUnitAmount($unit_amount);
            $line1->setTaxAmount($tax_amount);
            $line1->setLineAmount($line_amount);
            $line1->setDiscountRate($discount_rate); // Percentage

            // Add the line to the order
            $invoice->addLineItem($line1);

            // Repeat for all lines...

            $xero->save($invoice);
        }

    }

    public function setInvoicePaid($invoice_id){
        $xero    = new XeroPHP\Application\PrivateApplication();
        $invoice = $xero->loadByGUID('Accounting\\Invoice', $invoice_id);
        $invoice->setStatus('Paid');
        $xero->save();
    }

    public function updateInvoice($invoice_id, $params = []) {
        $xero    = new XeroPHP\Application\PrivateApplication();
        $invoice = $xero->loadByGUID('Accounting\\Invoice', $invoice_id);
        $invoice->setStatus('Paid');
        $xero->save();
    }

    public function addAttachment($invoice_id, $attachment_url) {
        $xero       = new XeroPHP\Application\PrivateApplication();
        $attachment = Facades\XeroAttachment::createFromLocalFile($attachment_url);

        $invoice = $xero->loadByGUID('Accounting\\Invoice', $invoice_id);
        $invoice->addAttachment($attachment);
    }

}
