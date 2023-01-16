<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_title',
        'user_id',
        'status',
        'tx_ref',
        'response_code',
        'amount',
        'flw_ref',
        'transaction_id',
        'currency',
        'payment_date'
    ];
}
