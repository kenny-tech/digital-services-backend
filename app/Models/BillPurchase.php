<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trans_ref',
        'amount',
        'smart_card_number',
        'provider',
        'status'
    ];
}
