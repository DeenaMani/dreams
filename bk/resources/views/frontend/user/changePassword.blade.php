@extends('layouts.app')

@section('main-content')
    <section class="account">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                     @include('frontend.user.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="dashboard">
                        <div class="title">
                            Personal Information!
                        </div>
                        <form id="change-password" action="{{route('changePasswordPost')}}"  method="post"> 
                             @csrf

                             @include('layouts.partials.messages')

                            <div class="row">

                                <div class="form-group mb-3 col-lg-7">
                                <!-- <label class="form-label" for="old-password">Old Password </label> -->
                                <input class="form-control" type="password" name="old_password" id="old_assword" value="" placeholder="Old Password" required>
                                
                            </div>

                            <div class="form-group mb-3 col-lg-7">
                                <!-- <label class="form-label" for="New Password"> New Password </label> -->
                                <input class="form-control" type="password" name="new_password" id="new_password" value="" placeholder="New Password" required>
                               
                            </div>

                            <div class="form-group mb-4 col-lg-7">
                                <!-- <label class="form-label" for="comform-password"> Conform New Password </label> -->
                                <input class="form-control" type="password" name="confirm_password" id="confirm_password" value="" placeholder="Confirm New Password" required>
                               
                            </div>

                            <div>
                                <button type="submit" class="btn-theme-1 py-2">Change Password</button>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script src="{{assets('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
    $('#change-password').validate({
     rules: {
        new_password: {
            required: true,
            minlength: 5
        },
        confirm_password: {
            required: true,
            minlength: 5,
            equalTo: "#new_password"
        }
    }
    });
</script>
    
@endpush