<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchoffers extends Model
{
    use HasFactory;
    protected $table = 'branchoffers';
    protected $fillable =['title','discounttype','value','createdby'];
}
