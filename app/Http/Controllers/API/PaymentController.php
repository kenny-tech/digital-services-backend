<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Models\AirtimePurchase;
use App\Models\BillPurchase;
use Illuminate\Http\Request;
use Str;
use DB;

class PaymentController extends BaseController
{
    public function index()
    {
        try {
           $payments = Payment::get();
           return $this->sendResponse($payments, 'Payments successfully retrieved.');
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function create(PaymentRequest $request)
    {
        try {

            DB::beginTransaction();

            $data = [
                'payment_title' => $request->payment_title,
                'user_id' => $request->user_id,
                'status' => $request->status,
                'tx_ref' => $request->tx_ref,
                'response_code' => $request->response_code,
                'amount' => $request->amount,
                'flw_ref' => $request->flw_ref,
                'transaction_id' => $request->transaction_id,
                'currency' => $request->currency,
                'payment_date' => $request->payment_date,
            ];

            $payment = Payment::create($data);

            if($request->payment_title == 'Buy Airtime') 
            {
                $recharge_number = $this->rechargeNumber($request->phone_number, $request->amount, $request->payment_title, $request->biller_name);

                if($recharge_number->status == 'success') {
                    
                    AirtimePurchase::create([
                        'user_id' => $request->user_id,
                        'phone_number' => $request->phone_number,
                        'flw_ref' => $recharge_number->data->flw_ref,
                        'reference' => $recharge_number->data->reference,
                        'amount' => $recharge_number->data->amount,
                        'network' => $recharge_number->data->network,
                        'status' => $recharge_number->status,
                        'tx_ref' => $request->tx_ref,
                        'payment_id' => $payment->id
                    ]);
                    
                }
            } 
            else 
            {
                $recharge_number = $this->rechargeNumber($request->smart_card_number, $request->amount, $request->payment_title, $request->biller_name);

                if($recharge_number->status == 'success') {
                    
                    BillPurchase::create([
                        'user_id' => $request->user_id,
                        'smart_card_number' => $request->smart_card_number,
                        'flw_ref' => $recharge_number->data->flw_ref,
                        'reference' => $recharge_number->data->reference,
                        'amount' => $recharge_number->data->amount,
                        'network' => $recharge_number->data->network,
                        'status' => $recharge_number->status,
                        'tx_ref' => $request->tx_ref,
                        'payment_id' => $payment->id
                    ]);
                    
                }
            }
            DB::commit();
            
            if($payment != null) {
                return $this->sendResponse($payment, 'Payment successfully created.');
            } else {
                return $this->sendError('Unable to create payment. Please try again.');
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
            DB::rollback();
        }
    }

    public function getPayment(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $payments = Payment::where('user_id', $user_id)->get();
            return $this->sendResponse($payments, 'Payment retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function rechargeNumber($number, $amount, $title, $biller_name)
    {
        try {

            if($title === 'Buy Airtime') {
                $type = 'AIRTIME';
            } else {
                $type = $biller_name;
            }

            $token = env('FLUTTERWAVE_SECRET_KEY');

            // set post fields
            $post = array(
                'country' => 'NG',
                'customer' => $number,
                'amount' => $amount,
                'type' => $type,
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

    public function verifyPayment(Request $request)
    {
        try {

            $tx_ref = $request->tx_ref;

            $token = env('FLUTTERWAVE_SECRET_KEY');

            $ch = curl_init('https://api.flutterwave.com/v3/transactions/verify_by_reference?tx_ref='.$tx_ref);
            
             // Returns the data
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
             //Set your auth headers
             curl_setopt($ch, CURLOPT_HTTPHEADER, array(
             'Content-Type: application/json',
             'Authorization: Bearer ' . $token
             ));
             
             // get stringified data/output
             $response = curl_exec($ch);
             
             // get info about the request
            //  $info = curl_getinfo($ch);
            
             // close curl resource to free up system resources
             curl_close($ch);
 
             return json_decode($response);
        } catch (\Exception $e) {
            return back()->with(['error' => 'Oops! Something went wrong: ' . $e->getMessage()]);
        }
    }
}
