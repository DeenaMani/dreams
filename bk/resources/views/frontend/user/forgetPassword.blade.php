@extends('layouts.app')

@section('main-content')

<section class="login">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 m-auto">
                    <form id="forget-password-form" action="{{route('forgetPasswordPost')}}" method="post">
                        @csrf
                        <div class="title">Forget Your Password ?</div>
                        @include('layouts.partials.messages')
                        <div class="row">

                        <div class="form-group mb-4 ">
                            <!-- <label class="form-label" for="email"> Email* </label> -->
                            <input class="form-control" type="email" name="email" id="email" value="" placeholder="E Mail" required>
                          
                        </div>

                    

                        <div>
                            <button type="submit" class="btn-theme-1">Submit</button>
                        </div>

                        <div class="mt-4"><a href="{{url('login')}}"> Back To Login</a></div>

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
    $('#forget-password-form').validate({})
</script>
    
@endpush