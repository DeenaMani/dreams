<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeSession extends Model
{
    use HasFactory;

    protected  $table = "freesessions";
    
    protected $guarded = [];
}
