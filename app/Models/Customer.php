<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    // if your key name is not 'id'
    // you can also set this to null if you don't have a primary key

    // In Laravel 6.0+ make sure to also set $keyType

}

