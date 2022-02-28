<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
 
use DB;
use Session;
use App\Models\Employee;
//model for userslogin

use App\Models\Login;

class UserController extends Controller
{
    //
    public function login(Request $request)
    {
        try 
        {

        // Validating With email and password from the database LOGIN table
            $request->validate([
                'email'=>'required|email',
                'password'=>'required'
             ]);
        $email=$request->email;
        
        $password=$request->password;
        $user=Login::select('*')->
        where('email','=',$email)
        ->where('password','=',$password)->first();
       
        if($user==null)
        {
            Session::put('errormssg','Invalid Credentials');
            return view('index');
        }
        elseif($user->usertype=="super")
        {
            Session::put('name',$user->name);
            Session::put('email',$user->email);
            Session::put('usertype',$user->usertype);
            

           
            return view('admin');
        }
        elseif($user->usertype=="outlet")
        {
            Session::put('name',$user->name);
            Session::put('email',$user->email);
            Session::put('usertype',$user->usertype);
            Session::put('outletid',$user->outletid);
           
            return view('outletdashboard');
        }
        elseif($user->usertype=="outletemployee")
        {
            Session::put('name',$user->name);
            Session::put('email',$user->email);
            Session::put('usertype',$user->usertype);
            Session::put('employeeid',$user->employeeid);
            Session::put('outletid',$user->outletid);
            
           return redirect('outletemployeedashboard');
            
        }
        elseif($user->usertype=="branch")
        {
            Session::put('name',$user->name);
            Session::put('email',$user->email);
            Session::put('usertype',$user->usertype);
            Session::put('employeeid',$user->employeeid);
            Session::put('branchid',$user->branchid);
            
           return view('branchdashboard');
            
        }
        else
        {
            return view('error');
        }
        } 
        catch (\Exception $e) 
        {

    return $e->getMessage();
}

}
public function forget_password()
{
    return view('forgetpassword');
}
public function forgetpassword(Request $request)
{
    $email=$request->email;
    $name=$request->name;
    $user=Login::select('*')->
    where('email','=',$email)
    ->where('name','=',$name)->first();
    if($user==null)
    {
        Session::put('errormssg','Invalid Credentials');
        return back();
    }
    else
    {
        return view('updatepassword')->with('user',$user);
    }
}

public function branchemployee(Request $request)
    {
        // dd($request);
         if(Employee::where('phone', '=', $request->phone)->first())
            {
                Session::put('error_mssg','Employee Already Exists');
                return back();
            }
            else
            {
                // $request->validate([
                //     'name'=>'required',
                //     'phone'=>'required',
                //     'email'=>'required|email',
                //     'address'=>'required',
                //     'joindate'=>'required',
                //     'dateofbirth'=>'required',
                //     'basicsalary'=>'required',
                //     'workingtime'=>'required',
                //     'accno'=>'required|integer',
                //     'IFSC'=>'required',
                //     'branch'=>'required',
                //     'designation'=>'required',
                //     'outletid'=>'required|integer',
                //     'remarks'=>'nullable',
                //     'login'=>'required',
                //     'password'=>'required',
                // ]);
                Employee::create([
                    'name'=>request('name'),
                    'phone'=>request('phone'),
                    'email'=>request('email'),
                    'address'=>request('address'),
                    'joindate'=>request('joindate'),
                    'dateofbirth'=>request('dateofbirth'),
                    'basicsalary'=>request('basicsalary'),
                    'workingtime'=>request('workingtime'),
                    'accno'=>request('accno'),
                    'IFSC'=>request('IFSC'),
                    'branch'=>request('branch'),
                    'designation'=>request('designation'),
                    'outletid'=>request('outletid'),
                    'branchid'=>request('branchid'),
                    'remarks'=>request('remarks'),
                    'login'=>request('login'),
                    'password'=>request('password'),
                    'createdby'=>session()->get('name'),
                ]);

                $usertype='employee';
                $id=Employee::latest()->first();
                // dd($id);
                Login::create([
                'name'=>request('name'),
                'email'=>request('email'),
                'phone'=>request('phone'),
                'address'=>request('address'),
                'password'=>request('password'),
                'usertype'=>$usertype,
                'outletid'=>0,
                'branchid'=>request('branchid'),
                'employeeid'=>$id->empid,
                'createdby'=>session()->get('name'),
            ]);

            if($request->hasfile('photo'))
            {
                // dd('1');
                $i=0;
               foreach($request->file('photo') as $file)
               {   
                   // dd($request->count);
                   $i++;
                   $name = $id->empid.'_'.$i.'.jpg';
                   $file->move(public_path().'/branch/employee/', $name);  
                   $data[] = $name;  
               }
            }
            if($request->hasfile('file'))
            {
                // dd('2');
                $i=0;
               foreach($request->file('file') as $file)
               {   
                   // dd($request->count);
                   $i++;
                   $name = $id->empid.'_'.$i.'.jpg';
                   $file->move(public_path().'/employee/documents/', $name);  
                   $data[] = $name;  
               }
            }
                return redirect('branchdashboard');
            }
       
    }
    //viewemploee list
     public function viewemployee()
    {
        try
        {
           
        $viewemployee=DB::table('employee')->where('branchid','=',session()->get('branchid'))->where('status',1)->latest()->get();

        return view('admin.branch.viewemployee')->with('viewemployee',$viewemployee);
           

    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    public function editbranchemployee($id)
    {
        $employee=Employee::where('empid','=',$id)->first();
        $login=Login::where('employeeid','=',$id)->first();
        return view('admin.branch.editemployee')->with('employee',$employee)->with('login',$login);
    }
    
    //viewbranchbill
    
     public function viewbranchbill()
    {
        try
        {
           
        $viewbranchbill=DB::table('branchbilling')->latest()->get();

        return view('admin.branch.viewbranchbill')->with('viewbranchbill',$viewbranchbill);
           

    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
    public function adminviewbranchbill()
    {
        try
        {
           
        $adminviewbranchbill=DB::table('branchbilling')->latest()->get();

        return view('admin.adminviewbranchbill')->with('adminviewbranchbill',$adminviewbranchbill);
           

    }
    catch (\Exception $e)
    {

    return back()->withError('Please Check data '.$e->getMessage())->withInput();


    }
    }
   
   
}
