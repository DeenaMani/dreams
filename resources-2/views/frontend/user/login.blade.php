@extends('layouts.app')

@section('main-content')

<section class="login">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 m-auto">
                    <form id="login-form" action="{{route('loginPost')}}" method="post">
                        @csrf
                        <div class="title">Login To Your Register Account!</div>
                        @include('layouts.partials.messages')
                        <div class="row">

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="email"> Email* </label> -->
                            <input class="form-control" type="email" name="email" id="email" value="" placeholder="E Mail" required>
                          
                        </div>

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="password"> password* </label> -->
                            <input class="form-control" type="password" name="password" id="password" value="" placeholder="password" required>
                           
                        </div>

                        <div class="col-lg-6">
                            <!-- <label>
                                <input type="checkbox" name="remember-me"> Remember Me
                            </label> -->
                        </div>

                        <div class="col-lg-6 text-end">
                            <a href="{{url('forget-password')}}">Forget Your Password?</a>
                        </div>

                        <div>
                            <button type="submit" class="btn-theme-1">Login</button>
                        </div>

                        <div class="mt-4"><span class="text-muted">Not A member?</span><a href="{{url('register')}}"> Register Now</a></div>

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
    $('#login-form').validate({})
</script>
    
@endpush