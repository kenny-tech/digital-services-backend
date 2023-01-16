<?php

namespace App\Http\Controllers\API;

use App\Models\BillPurchase;
use Illuminate\Http\Request;

class BillController extends BaseController
{
    public function index()
    {
        try {
           $bills = BillPurchase::get();
           return $this->sendResponse($bills, 'Bills successfully retrieved.');
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

    public function getBills(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $bills = BillPurchase::where('user_id', $user_id)->get();
            return $this->sendResponse($bills, 'Bills retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('Oops! Something went wrong '.$e->getMessage());
        }
    }

}
