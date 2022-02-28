<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Billing;
use Session;
use App\Models\Billingproducts;
use Carbon\Carbon;
use App\Models\Style;
use App\Models\Stock;
use App\Models\Outlet;
use App\Models\Customer;
use App\Models\Color;
use App\Models\Outletstock;
use App\Models\Godown;
use App\Models\Outletstocklist;
use App\Models\Branch;
use App\Models\Branchstock;
use App\Models\Adminbill;
use App\Models\Branchbilling;
use App\Models\Branchreturnproducts;
use App\Models\Branchbillingproducts;
use App\Models\Branchreturnbill;



class SalesController extends Controller
{
    //
    public function bill()
    {
        
        return view('sales.add1');
    }
    
    public function getstocks($id)
    {
        // dd($id);
        $outletid=session()->get('outletid');
        $data=Stock::whereId($id)
        // $data=Outletstock::join('stock','outletstock.itemcode','stock.id')
        // ->select('outletstock.*','stock.status','stock.brandname','stock.catname','stock.subcatname','stock.stylename','stock.sizevalue','stock.color','stock.HSN','stock.wholesaleprice','stock.wholesalenotax','stock.retailprice','stock.retailnotax','stock.CGST','stock.SGST','stock.IGST','stock.catid','stock.subcatid','stock.styleid','stock.sizeid')
        // ->where('itemcode',$id)
        ->get();
        // $data=Stock::join('outletstock','stock.id','outletstock.itemcode')
        // ->select('stock.*','outletstock.quantity')
        // ->where('id',$id)->get();
        // dd($data);
        // return Response::json($data);
        $userData['data'] = $data;
        // dd($data);
     echo json_encode($userData);
     exit;
    }
    public function getstocksoutlet($id)
    {
        
      
        $data=Stock::whereId($id)
        
        ->get();
        
        $userData['data'] = $data;
    
     echo json_encode($userData);
     exit;
    }
     public function getbranchstocks($id)
    {
      
        $branchid=session()->get('branchid');
        // dd($branchid);
        $data=Branchstock::where('branchid','=',$branchid)->where('itemcode','=',$id)->where('branchid','=',$branchid)->get();
        
        $userData['data'] = $data;
       
     echo json_encode($userData);
     exit;
    }
    public function fetch($id)
    {
        
        $data=DB::table('customer')->where('phone',$id)->get();
        $count=count($data);
       
        if($count==0)
        {
            
        $data1=DB::table('outlet')->where('phone',$id)->get();
        $userData['data'] = $data1;
        
        }
        else
        {
        $userData['data'] = $data;
        }
        
     echo json_encode($userData);
     exit;
    }
    public function transaction(Request $request)
    {
        // dd($request);
        try
        {


         $subtotal=$request->finaltotal;
         
        // $total=$subtotal-$discount;
        // dd($total);
        $date=Carbon::now('Asia/Kolkata')->toDateString();
        $time=Carbon::now('Asia/Kolkata')->toTimeString();
        $billid=Billing::latest()->first('billid');
        $id=$billid->billid;
        
        $data=$request->itemcode;
       
        $hsn=$request->taxid;
        
        
        $description=$request->description;
       
        $catid=$request->catid;
        $subcatid=$request->subcatid;
        
        $styleid=$request->styleid;
        $quantity=$request->quantity;
        $price=$request->price;
        $total=$request->total;
        $taxid=$request->taxid;
        $sgst=$request->sgst;
        $cgst=$request->cgst;
        $igst=$request->igst;
        $itemcode=$request->itemcode;
        $i=0;
        $outletid=session()->get('outletid');
        for($j=0; $j < count($data); $j++)
        {
        if($data[$j]!=null)
        {
            $i++;
             
            $qty=Outletstock::where('outletid',$outletid)
        ->where('itemcode',$itemcode[$j])->first();
            
            Billingproducts::create([
            'billid'=>$id,
            'slno'=>$i,
            'outletid'=>session()->get('outletid'),
            'itemcode'=>$itemcode[$j],
            'description'=>$description[$j],
            'catid'=>$catid[$j],
            'subcatid'=>$subcatid[$j],
            'styleid'=>$styleid[$j],
            'quantity'=>$quantity[$j],
            'price'=>$price[$j],
            'total'=>$total[$j],
            'taxid'=>$taxid[$j],
            'SGST'=>$sgst[$j],
            'CGST'=>$sgst[$j],
            'IGST'=>$igst[$j],
            'status'=>1,
            'createdby'=>session()->get('name'),
                   
        ]);
            Outletstock::where('outletid',$outletid)
        ->where('itemcode',$itemcode[$j])
            ->decrement('quantity',$quantity[$j]);
        }
        
        
    
        }
        if($request->paymentmode=='Qrcode')
        {
            $tender=null;
            $changedue=null;
            $transactionid=$request->transactionid;
        }
        else
        {
            $changedue=$request->changedue;
            $tender=$request->totaltender;
            $transactionid=null;
        }
        
       

        // DB::table('stock')->decrement('quantity',$quantity[$j]);
        Billing::create([
            'billingtype'=>request('billingtype'),
            'customername'=>request('customername'),
            'phone'=>request('phone'),
            'email'=>request('email'),
            'outletid'=>request('outletid'),
            'address'=>request('address'),
            'paymentmode'=>request('paymentmode'),
            'subtotal'=>$subtotal,
            'totaltaxvalue'=>request('totaltaxvalue'),
            'totaldiscount'=>request('totaldiscount'),
            'totalsgst'=>request('totalsgst'),
            'totalcgst'=>request('totalcgst'),
            'totaligst'=>request('totaligst'),
            'totalamount'=>request('finaltotal'),
            'netamount'=>request('finaltotal'),
            'totaltender'=>$tender,
            'transactionid'=>$transactionid,
            'changedue'=>$changedue,
            'discount'=>request('discount'),
            'billingdate'=>$date,
            'billingtime'=>$time,
            'gstno'=>request('gstno'),
            'createdby'=>session()->get('name'),
        ]);
        
        //  
        

        // dd($price);
        
    // Session::put('successmssg','Successful Billing of Products ');
    $billingproducts=DB::table('billingproducts')->where('billid',$id)->get();
    $billing=DB::table('billing')->where('billid',$id)->first();
    // dd($billing);
    if(Customer::where('phone', '=', $request->phone)->first())
        {
           return view('sales.print')->with('billing',$billing)->with('billingproducts',$billingproducts);
        }
        else
        {
            Customer::create([
                'name'=>request('customername'),
                'address'=>request('address'),
                'email'=>request('email'),
                'phone'=>request('phone')
            ]); 
            return view('sales.print')->with('billing',$billing)->with('billingproducts',$billingproducts);
        }
    }
    catch (\Exception $e) 
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
        
    }
    public function history()
    {
        try
        {
       if(session()->get('usertype')=='super')
            {
                $billing=Billing::join('outlet','billing.outletid','outlet.id')
                ->select('billing.*','outlet.name')
                ->latest()->get();
                return view('sales.view')->with('billing',$billing);
       
            }
        else
            {   
                $outletid=session()->get('outletid');
                $billing=Billing::where('outletid',$outletid)->get();
                return view('sales.view')->with('billing',$billing);
            }
        }
            catch (\Exception $e) 
            {

                return back()->withError('Please Check data '.$e->getMessage())->withInput();
    
            }
    }
    public function showbill($id)
    {
        // dd($id);
       try
       {

        $billing=Billing::where('billid',$id)->latest()->first();
        // dd($billing);
        $bid=$billing->billid;
        // dd($bid);
        $outletid=session()->get('outletid');
        $billingproducts=Billingproducts::where('billid',$bid)->get();
        // dd($billingproducts);
        return view('sales.details')->with('billing',$billing)->with('billingproducts',$billingproducts);
    } catch (\Exception $e) 

    {
    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
    
    }
    public function movestocks() 
    {
        try
        {
            if (session()->get('usertype')=='super') 
        {
        $brand=DB::table('brand')->get();
        $category=DB::table('category')->get();
        $hsn=DB::table('hsn')->get();
        $outlet=Outlet::latest()->get();
        $godown=DB::table('godown')->get();
        $size=DB::table('size')->get();
        $color=Color::get();
        return view('sales.movestocks')->with('brand',$brand)->with('outlet',$outlet)->with('category',$category)->with('hsn',$hsn)
        ->with('godown',$godown)->with('size',$size)->with('color',$color);
    }
    else
    {
        return view('error');
    }
}
    catch (\Exception $e) 

    {
    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
    
        
    }

    public function fetchstocks()
    {
        try
        {
        $stock=Stock::latest()->get();
        $outlet=Outlet::latest()->get();
        $godown=DB::table('godown')->latest()->get();
        return view('sales.movestocks')->with('stock',$stock)->with('outlet',$outlet)->with('godown',$godown);
    }
    catch (\Exception $e) 
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }
    }
   
    public function getallstocks($id)
    {
        // dd($id);
        $data=Stock::where('godown',$id)->get();
        // return Response::json($data);
        $userData['data'] = $data;

     echo json_encode($userData);
     exit;
       
    }
//     public function move_stocks(Request $request)
//     {
        
//      try{  

//        $count=$request->hiddencount;
//        $minquantity="minquantity";
//         $itemcode="itemcode";
//         $item="quantity";
//        for ($i=1; $i <$count ; $i++) 
//        {        
//         $item.=$i;
//         $minquantity.=$i;
//         $itemcode.=$i;
        
//         if($request->$item!=null)
//         {         
           
//             if(Outletstock::where('outletid',$request->outlet)->where('itemcode','=',$request->$itemcode)->first())
//             {

//                 Outletstock::where('outletid',$request->outlet)->where('itemcode','=',$request->$itemcode)->increment('quantity',$request->$item);
//                 $request->validate([
//                     'minquantity'=>'nullable',
//                 ]);
//                 // dd($request->$minquantity);
//                 Outletstock::where('outletid',$request->outlet)->where('itemcode','=',$request->$itemcode)->
//                 increment('minquantity',$request->$minquantity);
//                 Session::put('success_mssg','Stock Already Exist Quantity Updated');
//             Stock::where('godown','=',$request->godown)
//            ->where('id','=',$request->$itemcode)
//            ->decrement('quantity',$request->$item);
//           }
//            Outletstock::create([
//                 'outletid'=>request('outlet'),
//                 'itemcode'=>$request->$itemcode,                
//                 'quantity'=>$request->$item,
//                 'minquantity'=>$request->$minquantity,
//                 'createdby'=>session()->get('name'),
//                 ]);
//             Outletstocklist::create([
//                 'outletid'=>request('outlet'),
//                 'itemcode'=>$request->$itemcode,                
//                 'quantity'=>$request->$item,
//                 'minquantity'=>$request->$minquantity,
//                 'createdby'=>session()->get('name'),   
//             ]);
//           Stock::where('godown','=',$request->godown)
//            ->where('id','=',$request->$itemcode)
//            ->decrement('quantity',$request->$item);
//             Session::put('success_mssg','Stock Added Successfully to outlet');
              
//           }

//       }
//      return view('sales.movement');

//           }

//      catch (\Exception $e) 
//      {
//         // dd($e);
//     return back()->withError('Please Check data '.$e->getMessage())->withInput();
//     }
//     return view('dashboard');
// }
    

    // return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

public function searchkeyword(Request $request)
    {
        
        // dd($request);

        $stock=Stock::where('id',$request->keyword)
        ->orWhere('brandname',$request->keyword)
        ->orWhere('subcatname',$request->keyword)
        ->orWhere('stylename',$request->keyword)
        ->orWhere('catname',$request->keyword)
        ->orWhere('sizevalue',$request->keyword)
        ->orWhere('quantity',$request->keyword)
        ->orWhere('price',$request->keyword)
        ->get();
        // dd($stock);
        if(count($stock)==null)
        {
            Session::put('stock','No Stock Found');
        }
       $godown=DB::table('godown')->latest()->get();
        $branch=Branch::latest()->get();
        return view('admin.branch.movestock')->with('stock',$stock)->with('godown',$godown)->with('branch',$branch)->with('request',$request);
    }
   
    public function return_purchase()
    {
       return view('sales.return');
    }
    public function returnproduct(Request $request,$id)
    {
        // dd($request);
        $data=$request->itemcode;
        $quantity=$request->qty;
        $total=0;
        for($i=0;$i <count($data) ;$i++)
        {
            // dd($request);

            $result=Billingproducts::where('billid',$id)

            ->where('itemcode',$data[$i])
            ->first();
            
            
            Billingproducts::where('billid',$id)
            ->where('itemcode',$data[$i])
            ->decrement('quantity',$quantity[$i]);

            Billingproducts::where('billid',$id)
            ->where('itemcode',$data[$i])
            ->decrement('status',1);

            Outletstock::where('outletid',$result->outletid)
            ->where('itemcode',$result->itemcode)
            ->increment('quantity',$quantity[$i]);

           $total=$result->price*$quantity[$i];

           // dd($total);
        }
        Billing::where('billid',$id)
        ->increment('returnprice',$total);
        Billing::where('billid',$id)
        ->decrement('netamount',$total);
        Session::put('success_mssg','Product Returned');
        return back(); 
    }
    public function stocks()
    {
      $stock=DB::table('stock')->where('status',1)->latest()->get();
      $outlet=DB::table('outlet')->latest()->get();
      $godown=DB::table('godown')->latest()->get();
        return view('reports.stocks')->with('stock',$stock)->with('outlet',$outlet)->with('godown',$godown);
    }
    
    
public function branchstock()
    {
     
      
      $branchstock=DB::table('branchstock')->where('branchid',session()->get('branchid'))->where('status',1)->latest()->get();
     
        return view('admin.branch.branchstock')->with('branchstock',$branchstock);
    }
    
     public function editbranchstock($id)
  {
      $branchstock=branch::where('id',$id)->first();
      return view('admin.branch.editbranchstock')->with('branchstock',$branchstock);
  }
  public function updatebranchstock(Request $request,$id)
  {   
    
            
    $branchstock=$request->validate([
        'itemcode'=>'required',
        'quantity'=>'required',
        'retailprice'=>'retailprice',
        'address'=>'required',
        'updatedby'=>'required',
    ]);
    Branch::whereId($id)->update($branchstock);
    Session::put('success_mssg','branchstocks Updated Successfully');

     return redirect('branchstock');
  
}
  //DELETE BRANCH
  public function deletebranchstock($id)
    {
         if(session()->get('usertype')=='super')
            {
        Branch::whereId($id)->decrement('status',1);
        return back();
    }
    else
    {
        return view('error');
    }
   
    
}

    
    public function dashboard()
    {
        $stock=Stock::where('quantity','<','minquantity')->get();
        return view('dashboard')->with('stock',$stock);
    }
    public function importstock()
    {

        $godown=Godown::latest()->get();
        return view('sales.movement')->with('godown',$godown);
    }
    public function movetobranch()
    {
        $branch=Branch::get();
        $godown=Godown::get();
        return view('admin.branch.movestock')->with('branch',$branch)->with('godown',$godown);
    }
    public function movetooutlet()
    {
        $outlet=Outlet::latest()->get();
        $godown=Godown::latest()->get();
        return view('admin.outlets.movestock')->with('outlet',$outlet)->with('godown',$godown);
    }
    public function move_stocks(Request $request)
    {
        // dd($request);
        
     try{  

       $count=$request->hiddencount;
       // $minquantity="minquantity";
        $itemcode="itemcode";
        $item="quantity";
       for ($i=1; $i <$count ; $i++) 
       {        
        $item.=$i;
        // dd($item);
        // $minquantity.=$i;
        $itemcode.=$i;

        
        if($request->$item!=null)
        {         
            $data=Stock::whereId($request->$itemcode)->first();
            dd($data);
           
            if(Branchstock::where('outletid',$request->outlet)->where('itemcode','=',$request->$itemcode)->first())
            {

                Outletstock::where('outletid',$request->outlet)->where('itemcode','=',$request->$itemcode)->increment('quantity',$request->$item);
                $request->validate([
                    'minquantity'=>'nullable',
                ]);
                // dd($request->$minquantity);
                Outletstock::where('outletid',$request->outlet)->where('itemcode','=',$request->$itemcode)->
                increment('minquantity',$request->$minquantity);
                Session::put('success_mssg','Stock Already Exist Quantity Updated');
            Stock::where('godown','=',$request->godown)
           ->where('id','=',$request->$itemcode)
           ->decrement('quantity',$request->$item);
          }
           
          Stock::where('godown','=',$request->godown)
           ->where('id','=',$request->$itemcode)
           ->decrement('quantity',$request->$item);
            Session::put('success_mssg','Stock Added Successfully to outlet');
              
          }

      }
     return redirect('admindashboard');

          }

     catch (\Exception $e) 
     {
        // dd($e);
    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    }
    return redirect('admindashboard');
}
public function movethestocks(Request $request)
    {
        //  dd($request);
        $count=$request->count;
        $itemcode=$request->itemcode;
        $quantity=$request->quantity;
        
        for ($i=0; $i < $count; $i++) 
        { 
            // dd($i);
             $data=Stock::whereId($itemcode[$i])
            ->where('godown',$request->godownid)->first();
           if($quantity[$i]==null || $itemcode[$i]==null)
                 {
                     session::put('error_mssg','itemcode or quantity not entered');
                     return redirect('movetobranch');
                 }
            if($data->quantity < $quantity[$i])
            {
                
            Session::put('quantity','Quantity Not Sufficient In Godown');
             return redirect('movetobranch');   
            }
            if(Branchstock::where('itemcode','=',$data->id)->where('branchid','=',$request->branchid)->first())
            {
                
                Branchstock::where('itemcode','=',$itemcode[$i])->where('branchid','=',$request->branchid)->increment('quantity',$quantity[$i]);
                
                Stock::whereId($data->id)->where('godown',$request->godownid)->decrement('quantity',$quantity[$i]);
                
                Session::put('success_mssg','Quantity Updated Successfully');
                
                
            }
            else
            {
                Branchstock::create([
            'itemcode'=>$data->id,
            'branchid'=>$request->branchid,
            'status'=>1,      
            'brandname'=>$data->brandname,
            'catid'=>$data->catid,
            'subcatid'=>$data->subcatid,
            'brandid'=>$data->brandid,
            'catname'=>$data->catname,
            'subcatname'=>$data->subcatname,
            'stylename'=>$data->stylename,
            'styleid'=>$data->styleid,
            'quantity'=>$quantity[$i],
            'HSN'=>$data->HSN,
            'CGST'=>$data->CGST,
            'SGST'=>$data->SGST,
            'IGST'=>$data->IGST,
            'sizeid'=>$data->sizeid,
            'sizevalue'=>$data->sizevalue,
            'color'=>$data->color,
            'wholesaleprice'=>$data->wholesaleprice,
            'wholesalenotax'=>$data->wholesalenotax,
            'retailprice'=>$data->retailprice,
            'retailnotax'=>$data->retailnotax,
            'title'=>$data->title,
            'material'=>$data->material,
            'createdby'=>session()->get('name'),
                ]);
                
                Stock::whereId($data->id)->where('godown',$request->godown)->decrement('quantity',$quantity[$i]);
                Session::put('success_mssg','Item added Successfully to Branch');
                 
                 
            }

        }
        return redirect('movetobranch');
    }
    public function get_stocks($id,$godownid)
    {
        
        // dd($godownid);
        $item=Godown::whereId($godownid)->first();
        $data=Stock::whereId($id)
        ->where('godown','=',$item->id)
        ->where('quantity','>',0)
        ->get();
        
      
        $userData['data'] = $data;
       
     echo json_encode($userData);
     exit;
    }
     public function outlettransaction(Request $request)
    {
          dd($request);
         $count=$request->count;
         $itemcode=$request->itemcode;
         $quantity=$request->quantity;
         for ($i=0; $i < $count; $i++) 
        { 
            if($itemcode[$i]!=null)
            {
                 $data=Stock::whereId($itemcode)->first();
                if(Outletstock::where('outletid','=',$request->outletid)->where('itemcode','=',$itemcode[$i])->first())
                {
                $item=Outletstock::where('outletid','=',$request->outletid)->where('itemcode','=',$itemcode[$i])->increment('quantity',$quantity[$i]);
                    Stock::whereId($itemcode)->decrement('quantity',$quantity[$i]);
                }
                else
                {
                Outletstock::create([
            'itemcode'=>$data->id,
            'outletid'=>$request->outletid,
            'brandname'=>$data->brandname,
            'catid'=>$data->catid,
            'subcatid'=>$data->subcatid,
            'brandid'=>$data->brandid,
            'catname'=>$data->catname,
            'subcatname'=>$data->subcatname,
            'stylename'=>$data->stylename,
            'styleid'=>$data->styleid,
            'quantity'=>$quantity[$i],
            'HSN'=>$data->HSN,
            'CGST'=>$data->CGST,
            'SGST'=>$data->SGST,
            'IGST'=>$data->IGST,
            'sizeid'=>$data->sizeid,
            'sizevalue'=>$data->sizevalue,
            'color'=>$data->color,
            'wholesaleprice'=>$data->wholesaleprice,
            'wholesalenotax'=>$data->wholesalenotax,
            'retailprice'=>$data->retailprice,
            'retailnotax'=>$data->retailnotax,
            'title'=>$data->title,
            'price'=>$data->price,
            'material'=>$data->material,
            'createdby'=>session()->get('name'),
            ]);
            Stock::whereId($itemcode)->decrement('quantity',$quantity[$i]);
            
                }
            }
        }
        $outlet=Outlet::where($request->outletid)->first();
        $date=Carbon::now('Asia/Kolkata')->toDateString();
        $time=Carbon::now('Asia/Kolkata')->toTimeString();
        Adminbill::create([
            'billingtype'=>$request->billingtype,
            'customername'=>$outlet->name,
            'paymentmode'=>$request->paymentmode,
            'subtotal'=>$request->subtotal,
            'discount'=>$request->discount,
            'transactionid'=>$request->transactionid,
            'billingdate'=>$date,
            'billingtime'=>$time,
            'returnprice'=>null,
            'netamount'=>$request->subtotal,
            'createdby'=>session()->get('name'),
            ]);
        Session::put('success_mssg','Successfully Transfered the Stocks');
        return redirect('admindashboard');
   
}
 public function branchtransaction(Request $request)
    {
        // dd($request);
       


         $subtotal=$request->discountedamount;
         
        
        $date=Carbon::now('Asia/Kolkata')->toDateString();
        $time=Carbon::now('Asia/Kolkata')->toTimeString();
       
        
        $data=$request->itemcode;
       
        $hsn=$request->taxid;
        
        
        $description=$request->description;
       
        $catid=$request->catid;
        $subcatid=$request->subcatid;
        
        $styleid=$request->styleid;
        $quantity=$request->quantity;
        $price=$request->price;
        $total=$request->total;
        $taxid=$request->taxid;
        $sgst=$request->sgst;
        $cgst=$request->cgst;
        $igst=$request->igst;
        $itemcode=$request->itemcode;
        $i=0;
        $branchid=session()->get('branchid');
        // dd($branchid);
        if($request->paymentmode=='Qrcode')
        {
            $tender=null;
            $changedue=null;
            $transactionid=$request->transactionid;
        }
        else
        {
            $changedue=$request->changedue;
            $tender=$request->totaltender;
            $transactionid=null;
        }
         $totaltax=$request->discountedamount-$request->totaltaxvalue;
        //  dd($totaltax);
         
         if($request->location=='kerala')
         {
             $totaligst=0;
             $totalsgst=$totaltax/2;
         }
         else
         {
             $totaligst=$totaltax;
             $totalsgst=0;
         }
        Branchbilling::create([
            'billingtype'=>request('billingtype'),
            'customername'=>request('customername'),
            'phone'=>request('phone'),
            'email'=>request('email'),
            'address'=>request('address'),
            'branchid'=>request('branchid'),
            'paymentmode'=>request('paymentmode'),
            'totaltaxvalue'=>request('totaltaxvalue'),
            'subtotal'=>$subtotal,
            'discount'=>request('discount'),
            'totalsgst'=>$totalsgst,
            'totalcgst'=>$totalsgst,
            'totaligst'=>$totaligst,
            'gstno'=>request('gstno'),
            'netamount'=>$subtotal,
            'totaltender'=>$tender,
            'transactionid'=>$transactionid,
            'changedue'=>$changedue,
            'billingdate'=>$date,
            'billingtime'=>$time,
            'createdby'=>session()->get('name'),
        ]);
        $id=Branchbilling::latest()->first();
        // dd($id);
        for($j=0; $j < count($data); $j++)
        {
        if($data[$j]!=null)
        {
            $i++;
             
            $qty=Branchstock::where('branchid',$branchid)
        ->where('itemcode',$itemcode[$j])->first();
            
            Branchbillingproducts::create([
            'billid'=>$id->billid,
            'branchid'=>request('branchid'),
            'slno'=>$i,
            'itemcode'=>$itemcode[$j],
            'title'=>$description[$j],
            'quantity'=>$quantity[$j],
            'price'=>$price[$j],
            'total'=>$total[$j],
            'taxid'=>$taxid[$j],
            'sgst'=>$sgst[$j],
            'cgst'=>$cgst[$j],
            'igst'=>$igst[$j],
            'status'=>1,
            'createdby'=>session()->get('name'),
        ]);
            Branchstock::where('branchid',$branchid)
        ->where('itemcode',$itemcode[$j])
            ->decrement('quantity',$quantity[$j]);
        }
        
        
    
        }
        
       
    $billingproducts=DB::table('branchbillingproducts')->where('billid',$id->billid)->get();
    $billing=DB::table('branchbilling')->where('billid',$id->billid)->first();
//   dd($billing);
    if(Customer::where('phone', '=', $request->phone)->first())
        {
           return view('admin.branch.print')->with('billing',$billing)->with('billingproducts',$billingproducts);
        }
        else
        {
            Customer::create([
                'name'=>request('customername'),
                'address'=>request('address'),
                'email'=>request('email'),
                'phone'=>request('phone')
            ]); 
            return view('admin.branch.print')->with('billing',$billing)->with('billingproducts',$billingproducts);
        }
    
        
    }
    public function getdetails($id)
    {
        $billing=Branchbilling::where('billid','=',$id)->where('branchid','=',session()->get('branchid'))->first();
        $billingproduct=Branchbillingproducts::where('billid','=',$id)->where('branchid','=',session()->get('branchid'))->get();
        return view('admin.branch.getdetails')->with('billing',$billing)->with('billingproduct',$billingproduct);
    }
    
     public function viewcustomer()
    {
        try
        {
           
        $viewcustomer=DB::table('customer')->where('branchid','=',session()->get('branchid'))->latest()->get();

        return view('admin.branch.viewcustomer')->with('viewcustomer',$viewcustomer);
           

    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    public function returnbranchproduct(Request $request,$id)
    {
        // dd($request);
        $data=$request->itemcode;
        $quantity=$request->qty;
        $total=0;
        for($i=0;$i <count($data) ;$i++)
        {
            // dd($request);

            $result=Branchbillingproducts::where('billid',$id)

            ->where('itemcode',$data[$i])
            ->first();
            
            $total+=($result->price*$quantity[$i]);
            Branchbillingproducts::where('billid',$id)
            ->where('itemcode',$data[$i])
            ->decrement('quantity',$quantity[$i]);

            Branchbillingproducts::where('billid',$id)
            ->where('itemcode',$data[$i])
            ->decrement('status',1);

            Branchstock::where('branchid',session()->get(''))
            ->where('itemcode',$result->itemcode)
            ->increment('quantity',$quantity[$i]);

           $total=$result->price*$quantity[$i];
           Branchreturnproducts::create([
               'billid'=>$id,
               'branchid'=>session()->get('branchid'),
               'itemcode'=>$data[$i],
               'quantity'=>$quantity[$i],
               'price'=>$result->price,
               'createdby'=>session()->get('name'),
               ]);

           
        }
        $bill=Branchbilling::where('billid','=',$id)->first();
        $date=Carbon::now('Asia/Kolkata')->toDateString();
        Branchreturnbill::create([
            'billid'=>$id,
            'branchid'=>session()->get('branchid'),
            'customername'=>$bill->customername,
            'totalprice'=>$bill->netamount,
            'returnprice'=>$total,
            'netamount'=>$bill->netamount-$total,
            'returndate'=>$date,
            'createdby'=>session()->get('name'),
            ]);
            
        Session::put('success_mssg','Product Returned');
        return back(); 
    }
    public function returnbranchbill()
    {
        $viewbranchbill=Branchreturnbill::latest()->get();
        return view('admin.branch.viewbranchreturnbill')->with('viewbranchbill',$viewbranchbill);
    }
    public function getreturndetails($id)
    {
        $data=Branchreturnbill::where('billid','=',$id)->first();
        $billingproduct=Branchreturnproducts::join('branchstock','branchreturnproducts.itemcode','branchstock.itemcode')->select('branchreturnproducts.*','branchstock.title')->where('billid','=',$id)->get();
        // dd($billingproduct);
        $billing=Branchbilling::where('billid','=',$id)->first();
        return view('admin.branch.getreturndetails')->with('billingproduct',$billingproduct)->with('billing',$billing)->with('data',$data);
    }
    public function branch_stock($id)
    {
     
      
      $branchstock=DB::table('branchstock')->where('branchid',$id)->where('status',1)->latest()->get();
     
        return view('admin.branchstock')->with('branchstock',$branchstock);
    }
    
     public function admingetdetails($id)
    {
        // dd('hai');
        $billing=Branchbilling::where('billid','=',$id)->first();
        $billingproduct=Branchbillingproducts::where('billid','=',$id)->get();
        // dd($billingproduct);
        return view('admin.admingetdetails')->with('billing',$billing)->with('billingproduct',$billingproduct);
    }
    
}
