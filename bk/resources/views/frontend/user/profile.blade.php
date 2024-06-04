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
                        <form id="account-details" action="{{route('saveProfile')}}" method="post">
                           @csrf

                            @include('layouts.partials.messages')
                            <div class="row">

                                <div class="form-group mb-3 col-lg-6">
                                <label class="form-label" for="first-name">First Name </label>
                                <input class="form-control" type="text" name="first_name" id="first-name" value="{{$user->first_name}}" placeholder="First Name">
                                
                            </div>

                            <div class="form-group mb-3 col-lg-6">
                                <label class="form-label" for="last-name">Last Name </label>
                                <input class="form-control" type="text" name="last_name" id="last-name"  placeholder="Last Name" value="{{$user->last_name}}">
                                
                            </div>
                         
                            <div class="form-group mb-3">
                                <label class="form-label" for="email"> E Mail </label>
                                <input class="form-control" type="text" name="email" id="email" value="{{$user->email}}" placeholder="E mail"  readonly>
                               
                            </div>

                            <div class="form-group mb-4">
                                <label class="form-label" for="mobile-number"> Mobile Number </label>
                                <input class="form-control" type="text" name="mobile" id="mobile-number" value="{{$user->phone}}" placeholder="Mobile Number">
                               
                            </div>

                            <div>
                                <button type="submit" class="btn-theme-1 py-2">Save</button>
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

@endpush