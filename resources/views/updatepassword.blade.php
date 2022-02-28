@extends('partials.login')

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo" align="center">
                <img src="../../images/logo_black.png" alt="logo" style="width: 150px; height: 120px;">
              </div>
              <div class="pt-3 form-group form-check-label " style="color: red">
                @if(session()->has('errormssg'))
                    {{ session()->get('errormssg') }}
                @endif
                @php
                Session::forget('errormssg');
                @endphp
            </div>
              <h4>Your Password Is</h4>
              <!-- <h6 class="fw-light">Your Password Is  </h6> -->
              <form method="POST" action="/updatepassword"class="pt-3">@csrf
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" id="exampleInputEmail1"  name="password" value="{{$user->password}}" disabled>
                </div>
                
               <a href="/">Home</a>
                <!-- <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Keep me signed in
                    </label>
                  </div>
                  <a href="#" class="auth-link text-black">Forgot password?</a>
                </div>  -->
                
                
              </form>
                
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  
 