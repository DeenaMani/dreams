<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingData extends Model
{
    use HasFactory;

    protected $tables = 'booking_data';

    protected $guarded = [ ];
}
