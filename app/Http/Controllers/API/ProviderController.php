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
            $token = env('FLUTTERWAVE_SECRET_KEY');

            //setup the request
            $ch = curl_init('https://api.flutterwave.com/v3/bill-categories?airtime=1');
            
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

    // public function ValidateCustomerPhoneNumber(Request $request)
    // {
    //     try {

    //         $token = env('FLUTTERWAVE_SECRET_KEY');

    //         // set post fields
    //         $post = array(
    //             'code' => 'BIL099',
    //             'customer' => '08098291822'
    //         );

    //         $ch = curl_init('https://api.flutterwave.com/v3/bill-items/AT099/validate');
            
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //          //Set your auth headers
    //          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    //             'Content-Type: application/json',
    //             'Authorization: Bearer ' . $token
    //         ));

    //         curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

    //         // execute!
    //         $response = curl_exec($ch);

    //         // close the connection, release resources used
    //         curl_close($ch);

    //         return $response;
    //     } catch (\Exception $e) {
    //         return back()->with(['error' => 'Oops! Something went wrong: ' . $e->getMessage()]);
    //     }
    // }

    public function buyAirtime(Request $request)
    {
        try {

            $token = env('FLUTTERWAVE_SECRET_KEY');

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

    public function getBillCategoriesForCableTv()
    {
        try {
            $token = env('FLUTTERWAVE_SECRET_KEY');

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