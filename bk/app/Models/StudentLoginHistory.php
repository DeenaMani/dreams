<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLoginHistory extends Model
{
    use HasFactory;

    protected $table = 'student_login_history';
    protected $guraded = [];
   // public $timestamps = false;
}
