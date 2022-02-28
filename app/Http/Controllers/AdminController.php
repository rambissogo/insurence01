<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\Branch;
use App\Models\Login;
use App\Models\Branchstock;

use DB;
use Session;
use Exception;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
class AdminController extends Controller
{
   
    public function addbranch(Request $request)
    {
      
     
            if(session()->get('usertype')=='super')
            {
        if(Branch::select('*')
        ->where('name', '=', $request->name)
        ->where('status','=','1')
        ->first())
        {
            Session::put('error_mssg','Branch Already Exists');
            
        }
        else
        if(Branch::select('*')
        ->where('name', '=', $request->name)
        ->where('status','=','0')
        ->first())
        {  
            $category=Branch::where('name',$request->name)->first();
            $id=$category->id;
            Branch::whereId($id)->increment('status',1);
        }
        else
        { 
            Branch::create([
            'name'=>request('name'),
            'phone'=> request('phone'),
            'email'=> request('email'),
            'address'=> request('address'),
            'createdby'=>session()->get('name'),
        ]);
        $usertype='branch';
        $id=Branch::latest()->first();
            Login::create([
             
            'name'=>request('name'),
            'email'=> request('email'),
            'phone'=> request('phone'),
             'outletid'=> 0,
             'branchid'=>$id->id,
            'employeeid'=> 0,
            'address'=> request('address'),
            'password'=> request('password'),
            'usertype'=> $usertype,
            'createdby'=>session()->get('name'),

            ]);
        $branch=DB::table('branch')->latest('created_at')->first();
        Session::put('success_mssg','Category Added Successfully');
        //dynamic table creations

           /* $table_name = 'branchstock'.$branch->id;

        $fields = [
             
            ['name' => 'status', 'type' => 'integer'],
            ['name' => 'brandid', 'type' => 'integer'],
              ['name' => 'brandname', 'type' => 'string'],
                ['name' => 'catname', 'type' => 'string'],
                  ['name' => 'catid', 'type' => 'string'],
                    ['name' => 'subcatname', 'type' => 'string'],
                      ['name' => 'subcatid', 'type' => 'string'],
                        ['name' => 'stylename', 'type' => 'string'],
                          ['name' => 'styleid', 'type' => 'string'],
                            ['name' => 'sizeid', 'type' => 'string'],
                              ['name' => 'sizevalue', 'type' => 'string'],
                                ['name' => 'vendorname', 'type' => 'string'],
                                  ['name' => 'color', 'type' => 'string'],
                                    ['name' => 'quantity', 'type' => 'string'],
         ['name' => 'minquantity', 'type' => 'integer'],
          ['name' => 'HSN', 'type' => 'integer'],
           ['name' => 'price', 'type' => 'integer'],
            ['name' => 'wholesaleprice', 'type' => 'integer'],
            ['name' => 'wholesalenotax', 'type' => 'integer'],
            ['name' => 'retailprice', 'type' => 'integer'],
            ['name' => 'retailnotax', 'type' => 'integer'],
            ['name' => 'CGST', 'type' => 'integer'],
            ['name' => 'SGST', 'type' => 'integer'],
               ['name' => 'IGST', 'type' => 'integer'],
                   ['name' => 'godown', 'type' => 'string'],
                       ['name' => 'material', 'type' => 'string'],
                           ['name' => 'title', 'type' => 'string'],
                               ['name' => 'count', 'type' => 'integer'],
                                 ['name' => 'createdby', 'type' => 'string'],
                                  
                                     ['name' => 'updatedby', 'type' => 'string'],
                                      
        ];    

        // check if table is not already exists
        try
        {
   if (!Schema::hasTable($table_name)) 
   {
            Schema::create($table_name, function (Blueprint $table) use ($fields, $table_name) 
            {
                $table->increments('id');
                if (count($fields) > 0) {
                    foreach ($fields as $field) {
                        $table->{$field['type']}($field['name']);
                    }
                }
                $table->timestamps();
            });

         
    }
     
        }
          catch (\Exception $e) 
    {
dd($e->getMessage());
    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }*/
    }
        
    }
    else
    {
        return view('error');
    }

    
   

      
        return view('admin.branch.add');
    
    }


    /*****************************************/



 public function createTable($table_name, $fields = [])
    {
        // check if table is not already exists
        if (!Schema::hasTable($table_name)) {
            Schema::create($table_name, function (Blueprint $table) use ($fields, $table_name) {
                $table->increments('id');
                if (count($fields) > 0) {
                    foreach ($fields as $field) {
                        $table->{$field['type']}($field['name']);
                    }
                }
                $table->timestamps();
            });

         
        }

      
    }

     public function operate()
    {
        // set dynamic table name according to your requirements

       

        return $this->createTable($table_name, $fields);
    }
        public function removeTable($table_name)
    {
        Schema::dropIfExists($table_name); 
        
        return true;
    }

    /****************************************/
    public function branch()
    {
        
        $branch=Branch::where('status', '=',1)->get();
       return view('admin.branch.index')->with('branch',$branch);
    }
    public function editbranch($id)
  {
      $branch=Branch::where('id',$id)->first();
    //   dd($branch);
      $login=Login::where('branchid',$id)->first();
    //   dd($login);
      return view('admin.branch.edit')->with('branch',$branch)->with('login',$login);
  }
  //UPDATE BRANCH
  public function updatebranch(Request $request,$id)
  {   
    
            
    $branch=$request->validate([
        'name'=>'required',
        'phone'=>'required',
        'email'=>'email',
        'address'=>'required',
        'updatedby'=>'required',
    ]);
    Branch::whereId($id)->update($branch);
    Session::put('success_mssg','branch Updated Successfully');

     return redirect('view');
  
}
  //DELETE BRANCH
  public function deletebranch($id)
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

public function addoutlet(Request $request)
    {
      
     
            if(session()->get('usertype')=='super')
            {
        if(Outlet::select('*')
        ->where('name', '=', $request->name)
        ->where('status','=','1')
        ->first())
        {
            Session::put('error_mssg','outlet Already Exists');
            
        }
        else
        if(Outlet::select('*')
        ->where('name', '=', $request->name)
        ->where('status','=','0')
        ->first())
        {  
            $category=Outlet::where('name',$request->name)->first();
            $id=$category->id;
            Branch::whereId($id)->increment('status',1);
        }
        else
        { 
            Outlet::create([
            'name'=>request('name'),
            'phone'=> request('phone'),
            'email'=> request('email'),
            'address'=> request('address'),
            'createdby'=>session()->get('name'),
        ]);
        $outletid=Outlet::latest()->first();
            $usertype='outlet';
            Login::create([
            'name'=>request('name'),
            'email'=> request('email'),
            'phone'=> request('phone'),
            'outletid'=>$outletid->id,
            'employeeid'=> 0,
            'address'=> request('address'),
            'password'=> request('password'),
            'usertype'=> $usertype,
            'createdby'=>session()->get('name'),

            ]);
        $outlet=DB::table('outlet')->latest('created_at')->first();
        Session::put('success_mssg','Outlet Added Successfully');
        //dynamic table creations

           /* $table_name = 'branchstock'.$branch->id;

        $fields = [
             
            ['name' => 'status', 'type' => 'integer'],
            ['name' => 'brandid', 'type' => 'integer'],
              ['name' => 'brandname', 'type' => 'string'],
                ['name' => 'catname', 'type' => 'string'],
                  ['name' => 'catid', 'type' => 'string'],
                    ['name' => 'subcatname', 'type' => 'string'],
                      ['name' => 'subcatid', 'type' => 'string'],
                        ['name' => 'stylename', 'type' => 'string'],
                          ['name' => 'styleid', 'type' => 'string'],
                            ['name' => 'sizeid', 'type' => 'string'],
                              ['name' => 'sizevalue', 'type' => 'string'],
                                ['name' => 'vendorname', 'type' => 'string'],
                                  ['name' => 'color', 'type' => 'string'],
                                    ['name' => 'quantity', 'type' => 'string'],
         ['name' => 'minquantity', 'type' => 'integer'],
          ['name' => 'HSN', 'type' => 'integer'],
           ['name' => 'price', 'type' => 'integer'],
            ['name' => 'wholesaleprice', 'type' => 'integer'],
            ['name' => 'wholesalenotax', 'type' => 'integer'],
            ['name' => 'retailprice', 'type' => 'integer'],
            ['name' => 'retailnotax', 'type' => 'integer'],
            ['name' => 'CGST', 'type' => 'integer'],
            ['name' => 'SGST', 'type' => 'integer'],
               ['name' => 'IGST', 'type' => 'integer'],
                   ['name' => 'godown', 'type' => 'string'],
                       ['name' => 'material', 'type' => 'string'],
                           ['name' => 'title', 'type' => 'string'],
                               ['name' => 'count', 'type' => 'integer'],
                                 ['name' => 'createdby', 'type' => 'string'],
                                  
                                     ['name' => 'updatedby', 'type' => 'string'],
                                      
        ];    

        // check if table is not already exists
        try
        {
   if (!Schema::hasTable($table_name)) 
   {
            Schema::create($table_name, function (Blueprint $table) use ($fields, $table_name) 
            {
                $table->increments('id');
                if (count($fields) > 0) {
                    foreach ($fields as $field) {
                        $table->{$field['type']}($field['name']);
                    }
                }
                $table->timestamps();
            });

         
    }
     
        }
          catch (\Exception $e) 
    {
dd($e->getMessage());
    return back()->withError('Please Check data '.$e->getMessage())->withInput();
    

    }*/
    }
        
    }
    else
    {
        return view('error');
    }

   

      
        return view('admin.outlets.add');
    
    }


    /*****************************************/



 

    /****************************************/
    public function outlet()
    {
        
        $outlet=Outlet::where('status', '=',1)->get();
       return view('admin.outlets.index')->with('outlet',$outlet);
    }

public function editoutlet($id)
  {
      $outlet=outlet::where('id',$id)->first();
      $login=Login::where('outletid','=',$outlet->id)->first();
      return view('admin.outlets.edit')->with('outlet',$outlet)->with('login',$login);
  }
  //UPDATE BRANCH
  public function updateoutlet(Request $request,$id)
  {   
    
            
    $outlet=$request->validate([
        'name'=>'required',
        'phone'=>'required',
        'email'=>'email',
        'address'=>'required',
        'updatedby'=>'required',
    ]);
    Outlet::whereId($id)->update($outlet);
    Session::put('success_mssg','outlet Updated Successfully');

     return redirect('viewoutlet');
  
}
  //DELETE BRANCH
  public function deleteoutlet($id)
    {
           if(session()->get('usertype')=='super')
            {
        Outlet::whereId($id)->decrement('status',1);
        return back();
    }
    else
    {
        return view('error');
    }
    
    
}
    public function outletbilling()
{
    return view('admin/outlets/outletbilling');
}
 public function branchbilling()
{
    return view('admin/branch/branchbilling');
}
public function viewbranches()
{
    $branch=Branch::latest()->get();
    return view('admin.branch.index')->with('branch',$branch);
}
public function branchstocklist()
{
    $branchstocklist=Branchstock::join('branch','branchstock.branchid','branch.id')->select('branchstock.*','branch.name')->get();
    return view('admin.branchstocklist')->with('branchstocklist',$branchstocklist);
}
public function signout()
    {
        Session::flush();
        return redirect('/');
    }
}