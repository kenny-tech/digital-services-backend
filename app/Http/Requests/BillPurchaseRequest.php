<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillPurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required',
            'trans_ref' => 'required',
            'amount' => 'required',
            'smart_card_number' => 'required',
            'provider' => 'required',
            'status' => 'required',
        ];
    }
    
}
