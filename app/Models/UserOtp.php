<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;

    protected $table = 'user_otp';

    protected $fillable = [
        'user_id',
        'otp',
        'start_time',
        'end_time'
    ];

}
