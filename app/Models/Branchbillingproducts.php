<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchbillingproducts extends Model
{
    use HasFactory;
    protected $table = 'branchbillingproducts';
    protected $fillable =['billid','slno','itemcode','title','quantity','price','total','taxid','sgst','cgst','igst','status','createdby','branchid'];
}
