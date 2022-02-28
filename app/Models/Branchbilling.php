<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branchbilling extends Model
{
    use HasFactory;
    protected $table = 'branchbilling';
    protected $fillable =['branchid','billingtype','customername','phone','email','address','paymentmode','subtotal','totaltaxvalue','discount','transactionid','totalsgst','totalcgst','totaligst','billingdate','billingtime','netamount','totaltender','changedue','createdby'];
}
