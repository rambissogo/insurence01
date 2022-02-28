<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchreturnproducts extends Model
{
    use HasFactory;
    protected $table = 'branchreturnproducts';
     protected $fillable =['billid','branchid','itemcode','quantity','price','createdby'];
}
