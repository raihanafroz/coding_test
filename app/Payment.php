<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = [
      'user_id',
      'amount',
      'card_brand',
      'payment_at',
      'status',
    ];
}
