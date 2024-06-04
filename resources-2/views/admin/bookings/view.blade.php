@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Purchase Details</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h4>Order Id: {{$result->booking_no}}</h4>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')

                         <div class="row">
                            <div class="col-lg-6">
                                <h3>Purchase Details</h3>
                                <table class="table table-bordered  ">
                                    <tr>
                                        <th width="200">Order No</th>
                                        <td>{{$result->booking_no}}</td>
                                    </tr>
                                    <tr data-id="{{$result->id}}">
                                        <th width="200">Booking Status</th>
                                         <td> <select class="form-control booking_status">
                                            <option value="">Select</option>
                                            <option value="1" {{$result->booking_status == 1 ? 'selected' :'' }} >Success</option>
                                            <option value="2" {{$result->booking_status == 2 ? 'selected' :'' }} >Failed</option>
                                            <option value="3" {{$result->booking_status == 3 ? 'selected' :'' }} >Cancelled</option>
                                        </select>
                                    </td>
                                    </tr>
                                    <tr data-id="{{$result->id}}">
                                        <th width="200">Payment Status</th>
                                         <td> <select class="form-control payment_status">
                                            <option value="">Select</option>
                                            <option value="1" {{$result->payment_status == 1 ? 'selected' :'' }}>Paid</option>
                                            
                                            <option value="2" {{$result->payment_status == 2 ? 'selected' :'' }}>Failed</option>
                                            <option value="3" {{$result->payment_status == 3 ? 'selected' :'' }}>Refund</option>
                                            <option value="0" {{$result->payment_status == 0 ? 'selected' :'' }}>Pending</option>
                                        </select>
                                    </td>
                                    </tr>
                                    <tr>
                                        <th width="200">Total Amount</th>
                                        <td>{{$result->total_price." ".$result->currency}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Transaction</th>
                                        <td>{{$result->transcation_id}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Payment Type</th>
                                        <td>{{paymentType($result->payment_type)}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Purchased On</th>
                                        <td>{{$result->created_at}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Ip Address</th>
                                        <td>{{$result->ip_address}}</td>
                                    </tr>
                                </table>
                            </div>
                             <div class="col-lg-6">
                                <h3>Customer Details</h3>
                                <table class="table table-bordered  ">
                                    <tr>
                                        <th width="200">First Name</th>
                                        <td>{{$billingDetails->first_name}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Last Name</th>
                                        <td>{{$billingDetails->last_name}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Email</th>
                                        <td>{{$billingDetails->email}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Mobile</th>
                                        <td>{{$billingDetails->phone}}</td>
                                    </tr>
                                    <tr>
                                        <th width="200">Address</th>
                                        <td>{{$billingDetails->address}}</td>
                                    </tr>
                                       <tr>
                                        <th width="200">City</th>
                                        <td>{{$billingDetails->city}}</td>
                                    </tr>

                                                                        <tr>
                                        <th width="200">State</th>
                                        <td>{{$billingDetails->state_name}}</td>
                                    </tr>

                                                                       <tr>
                                        <th width="200">Pincode</th>
                                        <td>{{$billingDetails->pincode}}</td>
                                    </tr>



                                </table>
                            </div>

                             <div class="col-lg-12">
                                <h3>Course Information</h3>
                                <table class="table table-bordered  ">
                                    <tr>
                                        <td>SNO</td>
                                        <td>Course Name</td>
                                        <td>Course Price</td>
                                    </tr>

                                    @foreach($products as $key => $row)
                                    <tr>
                                        <td>{{$key + 1 }}</td>
                                        <td>{{$row->product_name}}</td>
                                        <td>{{$row->product_price." ".$result->currency}}</td>
                  
                                    </tr>
                                    @endforeach
                                </table>
                             </div>

                         </div>
                           
                    </div>
                </div> 
            </div>
        </div>
</div>
 @endsection

@push('scripts')
   
   <script type="text/javascript">
   
     $(document).on("change",".booking_status",function() {
         var status = $(this).val(); 
         var id = $(this).parents("tr").attr("data-id") ;
         //alert(id);
         $.ajax({
             url: "{{URL('/')}}/admin/bookings/booking-status/" + id + "/" +status ,
                 success: function(e) {
             }
         });
     });

      $(document).on("change",".payment_status",function() {
         var status = $(this).val(); 
         var id = $(this).parents("tr").attr("data-id") ;
         //alert(id);
         $.ajax({
             url: "{{URL('/')}}/admin/bookings/payment-status/" + id + "/" +status ,
                 success: function(e) {
             }
         });
     });
   </script>
@endpush