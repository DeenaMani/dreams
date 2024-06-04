<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetititeExamPdf extends Model
{
    use HasFactory;
    
    protected $table = 'competitite_exams_pdf';

    protected $guarded = [ ];
}
