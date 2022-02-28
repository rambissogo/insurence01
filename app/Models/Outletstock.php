<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outletstock extends Model
{
    use HasFactory;
    protected $table = 'outletstock';
    protected $fillable =['itemcode','material','title','brandname','catname','subcatname','stylename','outletid','catid','subcatid','styleid','HSN','quantity','price','color','wholesaleprice','wholesalenotax','retailprice','retailnotax','CGST','SGST','IGST'
    ,'sizeid','sizevalue','createdby'];

}
