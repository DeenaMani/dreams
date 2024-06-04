<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingBillingAddress extends Model
{
    use HasFactory;

    protected $table= 'booking_billing_address';

    protected $guarded = [ ];
}
