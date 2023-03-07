<?php
     
namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Str;
     
class ProviderController extends BaseController
{
    public function getBillCategories()
    {
        try {
            $token  = config('externalapi.flutterwave_s_key');

            //setup the request
            $ch = curl_init('https://api.flutterwave.com/v3/bill-categories');
            
            // Returns the data
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            //Set your auth headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
            ));
            
            // get stringified data/output
            $data = curl_exec($ch);
            
            // get info about the request
            $info = curl_getinfo($ch);
            // close curl resource to free up system resources
            curl_close($ch);

            return $data;
        } catch (\Exception $e) {
            return back()->with(['error' => 'Oops! Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function ValidateCustomer(Request $request)
    {
        try {

            $item_code = $request->item_code;
            $biller_code = $request->biller_code;
            $customer = $request->customer;

            $token  = config('externalapi.flutterwave_s_key');

            $ch = curl_init('https://api.flutterwave.com/v3/bill-items/'.$item_code.'/validate?code='.$biller_code.'&customer='.$customer);
            
             // Returns the data
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
             //Set your auth headers
             curl_setopt($ch, CURLOPT_HTTPHEADER, array(
             'Content-Type: application/json',
             'Authorization: Bearer ' . $token
             ));
             
             // get stringified data/output
             $data = curl_exec($ch);
             
             // get info about the request
             $info = curl_getinfo($ch);
             // close curl resource to free up system resources
             curl_close($ch);
 
             return $data;
        } catch (\Exception $e) {
            return back()->with(['error' => 'Oops! Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function buyAirtime(Request $request)
    {
        try {

            $token  = config('externalapi.flutterwave_s_key');

            $phone_number = $request->phone_number;
            $amount = $request->amount;

            // set post fields
            $post = array(
                'country' => 'NG',
                'customer' => $phone_number,
                'amount' => $amount,
                'type' => 'AIRTIME',
                'reference' => Str::random(10)
            );

            $ch = curl_init('https://api.flutterwave.com/v3/bills');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

             //Set your auth headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

            // execute!
            $response = curl_exec($ch);

            // close the connection, release resources used
            curl_close($ch);

            return json_decode($response);
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function payBills(Request $request)
    {
        try {

            $token  = config('externalapi.flutterwave_s_key');

            $smart_card_number = $request->smart_card_number;
            $amount = $request->amount;

            // set post fields
            $post = array(
                'country' => 'NG',
                'customer' => $smart_card_number,
                'amount' => $amount,
                'type' => 'GOTV Value',
                'reference' => Str::random(10)
            );

            $ch = curl_init('https://api.flutterwave.com/v3/bills');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

             //Set your auth headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

            // execute!
            $response = curl_exec($ch);

            // close the connection, release resources used
            curl_close($ch);

            return json_decode($response);
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function getBillCategoriesForCableTv()
    {
        try {
            $token  = config('externalapi.flutterwave_s_key');

            //setup the request
            $ch = curl_init('https://api.flutterwave.com/v3/bill-categories?cable=1');
            
            // Returns the data
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            //Set your auth headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
            ));
            
            // get stringified data/output
            $data = curl_exec($ch);
            
            // get info about the request
            $info = curl_getinfo($ch);
            // close curl resource to free up system resources
            curl_close($ch);

            return $data;
        } catch (\Exception $e) {
            return back()->with(['error' => 'Oops! Something went wrong: ' . $e->getMessage()]);
        }
    }


}