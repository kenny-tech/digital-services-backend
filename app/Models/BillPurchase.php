<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'smart_card_number',
        'flw_ref',
        'reference',
        'amount',
        'network',
        'status',
        'tx_ref',
        'payment_id'
    ];
}
