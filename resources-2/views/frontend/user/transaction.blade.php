@extends('layouts.app')

@section('main-content')
    <section class="account">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('frontend.user.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="exam-attempts">
                        <div class="exam-attempts-tables">
                            <div class="row align-items-center mb-2">
                                <div class="col-md-6">
                                    <div class="title">Transaction History</div>
                                </div>
                                <div class="col-md-6">
                                    <!-- <ul class="list-unstyled text-end">
                                        <li class="d-inline-block search">
                                            <label for="search" class="d-flex align-items-center"><input type="text" name="search" class="form-control d-inline-block ms-2" placeholder="Search List">
                                                <i class="fa fa-search"></i></label>
                                        </li>
                                    </ul> -->
                                </div>
                            </div>
                            <div class="table-content">
                                <table class="table mb-0 table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Courses</th>
                                            <th>Date</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <!-- <th>Validate</td> -->
                                        </tr>
                                    </thead>

                                    <tbody>
                                         @if(count($transactions))
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{$transaction->booking_no}}</td>
                                            <td>{{$transaction->product_names}}</td>
                                            <td>{{date("d M Y h:i A",strtotime($transaction->created_at))}}</td>
                                            <td>{{$transaction->total_price}} {{$transaction->currency}} </td>
                                            <td>{{bookingStatus($transaction->booking_status)}}</td>
                                            <!-- <td><a href=""><i class="fa fa-download pe-2"></i>Invoice</a></td> -->
                                        </tr>
                                       
                                          @endforeach
                                           @else
                                        <tr><td colspan="6" align="center">No Transaction</td></tr>
                                        @endif
                                     
                                    </tbody>
                                </table>
                                <!-- <div class="row align-items-center pt-4 pb-2">
                                    <div class="col-md-6">
                                        Showing 1 to 3 out of 10
                                    </div>
                                    <div class="pagination col-md-6 m-0">
                                        <ul class="list-unstyled ms-auto">
                                            <li><a href="" class=""><i class="fa fa-angle-left"></i></a></li>
                                            <li><a href="" class="active">1</a></li>
                                            <li><a href="" class="">2</a></li>
                                            <li><a href="" class="">3</a></li>
                                            <li><a href="" class=""><i class="fa fa-angle-right"></i></a></li>
                                        </ul>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')

@endpush