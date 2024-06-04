@extends('layouts.app')

@section('main-content')

    <section class="login">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 m-auto">
                      <form id="register" action="{{route('registerPost')}}" method="post">
                        @csrf
               
                        <div class="title pb-2">Register To Become Number!</div>
                          @include('layouts.partials.messages')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mb-2">
                                <!-- <label class="form-label" for="name"> Name* </label> -->
                                <input class="form-control" type="text" name="first_name" id="first_name" value="{{old('first_name')}}" placeholder="First Name" required>
                            </div>
                        </div>

                              <div class="col-lg-6">
                                    <div class="form-group mb-2">
                                    <!-- <label class="form-label" for="name"> Name* </label> -->
                                    <input class="form-control" type="text" name="last_name" id="last_name" value="{{old('last_name')}}" placeholder="Last Name" required>
                                </div>
                            </div>
                                
                        </div>

                        <div class="form-group mb-2">
                            <!-- <label class="form-label" for="email"> Email* </label> -->
                            <input class="form-control" type="email" name="email" id="email" value="{{old('email')}}" placeholder="E Mail" required>
                            
                        </div>


                        <div class="form-group mb-2">
                            <!-- <label class="form-label" for="email"> Email* </label> -->
                            <input class="form-control" type="text" name="mobile" id="mobile" value="{{old('mobile')}}" placeholder="Mobile">
                            
                        </div>
                     
                        <div class="form-group mb-2">
                            <!-- <label class="form-label" for="password"> password* </label> -->
                            <input class="form-control" type="password" name="password" id="password" value="" placeholder="Password" required>
                            
                        
                        </div>

                        <div class="form-group mb-4">
                            <!-- <label class="form-label" for="conform-password"> conform-password* </label> -->
                            <input class="form-control" type="password" name="confirm_password" id="confirm_password" value="" placeholder="Confirm Password" required>
                            
                        </div>

                        <div class="">
                            <label>
                                <input type="checkbox" name="remember-me" required> I Agree to Terms and Conditions
                            </label>
                            <label id="remember-me-error" class="error" for="remember-me"></label>
                        </div>

                        <p class="text-center">
                            <button type="submit" class="btn-theme-1">Register</button>
                        </p>

                        <div class="mt-4"><span class="text-muted">Already an Member?</span><a href="{{url('login')}}"> Login</a></div>

                      </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{assets('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
    $('#register').validate({
     rules: {
        password: {
            required: true,
            minlength: 5
        },
        confirm_password: {
            required: true,
            minlength: 5,
            equalTo: "#password"
        }
    }
    });
</script>
    
@endpush