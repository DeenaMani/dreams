<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $tables = 'categories';

    protected $guarded = [ ];


    public function course(): HasMany
    {
        return $this->hasMany(Course::class,'category_id','id')->where('courses.status','=', 1);
    }

}
