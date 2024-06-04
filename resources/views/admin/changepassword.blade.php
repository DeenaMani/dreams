@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Change Password</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Change Password</h1>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                        <form action="{{ route('admin.password.change') }}" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            @csrf
                            <div class="">
                                <div class="col-lg-6">
                                    <label for="old_passsword"> Old Password </label>
                                    <input type="text" class="form-control mb-3" name="old_password" required>
                                </div>  

                                <div class="col-lg-6">
                                    <label for="new_password"> New Password </label>
                                    <input type="text" class="form-control mb-3" name="new_password" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="confirm_password"> Confirm Password </label>
                                    <input type="text" class="form-control mb-3" name="confirm_password" required>
                                </div>
                               
                                <div class="col-lg-12 p-3 ">
                                    <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Change Password">
                                </div>
                            </div>                                   
                        </form>
                    </div>
                </div> 
            </div>
        </div>
</div>
@endsection
        