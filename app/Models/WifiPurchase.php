<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WifiPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_number',
        'flw_ref',
        'reference',
        'amount',
        'network',
        'status',
        'tx_ref',
        'payment_id'
    ];
}
