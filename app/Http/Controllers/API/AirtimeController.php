<?php

namespace App\Http\Controllers\API;

use App\Models\AirtimePurchase;
use Illuminate\Http\Request;

class AirtimeController extends BaseController
{
    public function index()
    {
        try {
           $payments = AirtimePurchase::get();
           return $this->sendResponse($payments, 'Airtime successfully retrieved.');
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function getAirtime(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $payments = AirtimePurchase::where('user_id', $user_id)->get();
            return $this->sendResponse($payments, 'Airtime retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

}
