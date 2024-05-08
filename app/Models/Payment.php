<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'payer_id',
        'amount',
        'currency',
        'payment_status',
        'payer_email',
        'user_id',
        'item_id'
    ];
}
