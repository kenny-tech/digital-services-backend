<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirtimePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trans_ref',
        'amount',
        'phone_number',
        'network',
        'status'
    ];
}
