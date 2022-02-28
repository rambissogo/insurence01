<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;
    protected $table = 'billing';
    protected $fillable =['billingtype','customername','email','phone','address','paymentmode','subtotal','totaltaxvalue','totaldiscount','totalsgst','totalcgst','totaligst','totalamount','totaltender','changedue','discount','billingdate','billingtime','gstno','pos','reversecharge','netamount','createdby','outletid'];
}
