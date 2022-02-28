<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billingproducts extends Model
{
    use HasFactory;
    protected $table = 'billingproducts';
    protected $fillable =['billid','slno','itemcode','description','catid','subcatid','styleid','quantity','price','total','taxid','SGST','CGST','IGST','status','createdby','outletid'];

}
