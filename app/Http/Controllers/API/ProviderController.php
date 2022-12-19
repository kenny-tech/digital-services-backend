<?php
     
namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Http\Controllers\API\BaseController as BaseController;
     
class ProviderController extends BaseController
{
    public function getBillerCategories()
    {
        $curlInit = curl_init();
        curl_setopt($curlInit, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: ' . 'InterswitchAuth ' . base64_encode(env('CLIENT_ID')),
        'Signature: ' . env('COMPUTED_SIGNATURE'), //check authentication section
        'TimeStamp: ' . Carbon::now()->timestamp,
        'Nonce: ' . env('COMPUTED_NONCE'), //check authentication section
        'TerminalID: ' . env('TERMINAL_ID'),
        'SignatureMethod: SHA1' 

        ));

        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlInit, CURLOPT_URL, 'https://sandbox.interswitchng.com/api/v2/quickteller/billers');

        $response = curl_exec($curlInit);
        curl_close($curlInit);
        return $response;
    }

    public function getBillerByCategories()
    {
        $curlInit = curl_init();
        curl_setopt($curlInit, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: ' . 'InterswitchAuth ' . base64_encode(env('CLIENT_ID')),
        'Signature: ' . env('COMPUTED_SIGNATURE'), //check authentication section
        'TimeStamp: ' . Carbon::now()->timestamp,
        'Nonce: ' . env('COMPUTED_NONCE'), //check authentication section
        'TerminalID: ' . env('TERMINAL_ID'),
        'SignatureMethod: SHA1' 

        ));

        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlInit, CURLOPT_URL, 'https://sandbox.interswitchng.com/api/v2/quickteller/categorys/<CATEGORY_ID>/billers');

        $response = curl_exec($curlInit);
        curl_close($curlInit);
        return $response;
    }

    public function getBillerPaymentItems()
    {
        $curlInit = curl_init();
        curl_setopt($curlInit, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: ' . 'InterswitchAuth ' . base64_encode(env('CLIENT_ID')),
        'Signature: ' . env('COMPUTED_SIGNATURE'), //check authentication section
        'TimeStamp: ' . Carbon::now()->timestamp,
        'Nonce: ' . env('COMPUTED_NONCE'), //check authentication section
        'TerminalID: ' . env('TERMINAL_ID'),
        'SignatureMethod: SHA1' 

        ));

        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlInit, CURLOPT_URL, 'https://sandbox.interswitchng.com/api/v2/quickteller/billers/<BILLER_ID>/paymentitems');

        $response = curl_exec($curlInit);
        curl_close($curlInit);
        return $response;
    }

    public function sendBillPaymentAdvice()
    {
        $curlInit = curl_init();
        curl_setopt($curlInit, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: ' . 'InterswitchAuth ' . base64_encode(env('CLIENT_ID')),
        'Signature: ' . env('COMPUTED_SIGNATURE'), //check authentication section
        'TimeStamp: ' . Carbon::now()->timestamp,
        'Nonce: ' . env('COMPUTED_NONCE'), //check authentication section
        'TerminalID: ' . env('TERMINAL_ID'),
        'SignatureMethod: SHA1' 
        
        ));
        
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlInit, CURLOPT_URL, 'https://sandbox.interswitchng.com/api/v2/quickteller/payments/advices');
        
        $fieldsString = json_encode(array(
            'customerId' => '00000000001',
            'customerMobile' => '2348033115478',
            'customerEmail' => 'johndoe@nomail.com',
            'amount' => 146000,
            'paymentCode' => '10801',
            'requestReference' => 1453 . '' . time() //This must be preceeded by the request prefix provided by Interswitch
        ));
        curl_setopt($curlInit, CURLOPT_POST, true);
        curl_setopt($curlInit, CURLOPT_POSTFIELDS, $fieldsString);
        
        $response = curl_exec($curlInit);
        curl_close($curlInit);
        return $response;
    }
    
}