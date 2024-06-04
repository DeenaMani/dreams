@php

$setting = App\Models\Setting::find(1);

@endphp

@extends('layouts.admin')

@section('main-content')

  <!-- Begin Page -->
  <div class="auth-pages">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="card">
            <div class="card-body p-4 p-md-5">
              <div class="clearfix text-center">
                <img src="{{url('public/image/setting/'.$setting->logo)}}" height="75px" alt="Admin">
              </div>
              <h5 class="mt-4 font-weight-600">Welcome back!</h5>
              <p class="text-muted mb-4">Enter your email address and password to access admin panel.</p>
              <form method="post" id="loginForm" action="{{ route('admin.login.perform') }}">
                   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                @include('layouts.partials.messages')

                <div class="form-group floating-label">
                <input type="email" class="form-control" id="email" name="username" @if(isset($_COOKIE["email"])) value="{{ $_COOKIE["email"]}}" @endif placeholder=""  />	
                  <label for="email">Email Address</label>
                  <div class="validation-error d-none font-size-13">
                    <p>Email must be a valid email address</p>
                  </div>
                </div>
                <div class="form-group floating-label">
                <input type="password" class="form-control" id="password"  @if(isset($_COOKIE["password"])) value="{{ $_COOKIE["password"]}}" @endif  name="password" placeholder="" minlength="6" />	
                  <label for="password">Password</label>
                  <div class="validation-error d-none font-size-13">
                    <p>This field is required</p>
                  </div>
                </div>

                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember" checked />
                    <label class="form-check-label" for="remember">Remember Me</label>
                  </div>
                </div>

                <div class="form-group text-center">
                  <button class="btn btn-primary btn-block" data-effect="wave" type="submit"> Log In
                  </button>
                </div>
               
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- end row -->
    </div>
    <!-- end container -->
  </div>

@endsection