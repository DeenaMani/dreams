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
                            Hello User !
                        </div>
                        <div class="text-muted">
                            From your account dashboard you can view your
                            <a class="btn-link" href="courses.html">courses</a>,
                            manage your
                            <a class="btn-link" href="account.html">account details</a>, <a class="btn-link" href="account.html">exam attempts</a>and
                            <a class="btn-link" href="password.html">edit your password
                                details.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')

@endpush