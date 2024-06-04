@extends('layout.admin')

@section('content')

<div class="main-content">
         <!-- content -->
         <div class="page-content">
            <!-- page header -->
            <div class="page-title-box">
               <div class="container-fluid">
                  <div class="page-title dflex-between-center">
                     <h3 class="mb-1 font-weight-bold">Orders</h3>
                     <ol class="breadcrumb mb-0 mt-1">
                        <li class="breadcrumb-item">
                           <a href="../index.html">
                              <i class="bx bx-home fs-xs"></i>
                           </a>
                        </li>
                        <li class="breadcrumb-item">
                           <a href="calender.html">
                              Apps
                           </a>
                        </li>
                        <li class="breadcrumb-item active">Orders</li>
                     </ol>
                  </div>
               </div>
            </div>
            <!-- page content -->
            <div class="page-content-wrapper mt--45">
               <div class="container-fluid">
                  <div class="card card-body">
                    <h3>Order Details</h3>

                    <div class="row">
                     <div class="col-lg-6">
                        <table class="table table-borderd">
                           <tr><td>Order No</td><td>{{$order->order_id}}</td></tr>
                           <tr><td>Product Name</td><td>{{$product->product_name}} </td></tr>
                           <tr><td>Payment Status</td><td>@if($order->payment_status == 1)
                                 <span class="badge badge-success">Paid</span>
                                 @elseif($order->payment_status == 0)
                                  <span class="badge badge-warning">Pending</span>
                                   @else
                                  <span class="badge badge-danger">Failed</span>
                                 @endif

                              </td></tr>
                           <tr><td>Order No</td><td>@if($order->order_status == 1)
                                 <span class="badge badge-success">Paid</span>
                                 @elseif($order->order_status == 0)
                                  <span class="badge badge-warning">Pending</span>
                                   @else
                                  <span class="badge badge-danger">Failed</span>
                                 @endif</td></tr>
                       
                            <tr><td>Amount</td><td>INR {{$order->total_amount}}</td>
                            <tr><td>Amount</td><td>{{$order->created_at}}</td>
                        </table>
                     </div>

                     <div class="col-xs-6">
                        <table class="table table-borderd">
                           <tr><td>Customer Name</td><td>{{$customer->first_name}} {{$customer->last_name}}</td></tr>
                          
                       
                            <tr><td>Email</td><td>{{$customer->email}}</td>
                            <tr><td>Phone No</td><td>{{$customer->telephone}}</td>
                            <tr><td>Address</td><td>{{$customer->address_1}}</td>
                            <tr><td>Postal Code</td><td>{{$customer->postcode}}</td>
                            <tr><td>State</td><td>{{$customer->state}}</td>
                        </table>
                     </div>


                     
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
@endsection