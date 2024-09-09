<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    //add protected fillable to enable mass input from our controller during transactions
    protected $fillable = [
        'transaction_ref_id',
        'transaction_ref_code',
        'customer_msisdn',
        'receiver_msisdn',
        'transaction_type_id',
        'transaction_amount',
        'transaction_cost',
        'transaction_code',
        'sender_balance',
        'receiver_balance'];
}


