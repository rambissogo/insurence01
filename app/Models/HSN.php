<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HSN extends Model
{
    use HasFactory;
    protected $table = 'hsn';
    protected $fillable =['HSNCode','CGST','SGST','IGST','createdby'];

}
