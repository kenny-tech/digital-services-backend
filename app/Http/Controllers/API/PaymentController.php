<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Models\AirtimePurchase;
use App\Models\BillPurchase;
use Illuminate\Http\Request;

class PaymentController extends BaseController
{
    public function index()
    {
        try {
           $payments = Payment::get();
           return $payments;
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function create(PaymentRequest $request)
    {
        try {
            $data = [
                'user_id' => $request->user_id,
                'payment_title' => $request->payment_title,
                'amount' => $request->amount,
                'status' => $request->status,
                'trans_ref' => $request->trans_ref,
            ];

            $payment = Payment::create($data);

            if($request->payment_title == 'Airtime purchase') {
                AirtimePurchase::create([
                    'user_id' => $request->user_id,
                    'trans_ref' => $request->trans_ref,
                    'amount' => $request->amount,
                    'phone_number' => $request->phone_number,
                    'network' => $request->network,
                    'status' => $request->status,
                ]);
            } else {
                BillPurchase::create([
                    'user_id' => $request->user_id,
                    'trans_ref' => $request->trans_ref,
                    'amount' => $request->amount,
                    'smart_card_number' => $request->smart_card_number,
                    'provider' => $request->provider,
                    'status' => $request->status,
                ]);
            }

            if($payment != null) {
                return $this->sendResponse($payment, 'Payment successfully created.');
            } else {
                return $this->sendError('Unable to create payment. Please try again.');
            }
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
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

}
