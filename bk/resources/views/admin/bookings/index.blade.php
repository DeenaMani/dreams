@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h2 class="mb-1 font-weight-bold text-white">Purchased History</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-header row">
                        <div class="col-lg-9">
                        <h2>Purchased History</h2> 
                        </div>

                        <div class="col-lg-3">
                        
                        </div>
                    </div>                
                 
                    <div class="card-body">
                         @include('layouts.partials.messages')
                         
                        <table class="table table-responsive mb-0" id="datatable">
                            <thead>
                                <tr>
                                    <th> Id </th>
                                    <th> Customer </th>
                                    <th> Booking No </td>
                                    <th> Booking Status </td>
                                    <th> Payment Status </td>
                                    <th> Transaction Id </th>
                                    <th> Total Amount </th>
                                    <th> Booked On </th>
                                    <th> Action </th>
                            </thead>
                            
                            <tbody>
                                @foreach ($results as $key => $data)
                                <tr data-id="{{$data->id}}">
                                    <td> {{$key + 1 }} </td>
                                    <td> {{$data->first_name." ".$data->last_name}} <br> {{$data->email}}</td>
                                    <td> <a href="{{ url('admin/bookings/'.$data->id.'/view') }}" >{{$data->booking_no}}</a></td>
                                    <td> <select class="form-control booking_status">
                                            <option value="">Select</option>
                                            <option value="1" {{$data->booking_status == 1 ? 'selected' :'' }} >Success</option>
                                            <option value="2" {{$data->booking_status == 2 ? 'selected' :'' }} >Failed</option>
                                            <option value="3" {{$data->booking_status == 3 ? 'selected' :'' }} >Cancelled</option>
                                        </select>
                                    </td>
                                   <td> <select class="form-control payment_status">
                                            <option value="">Select</option>
                                            <option value="1" {{$data->payment_status == 1 ? 'selected' :'' }}>Paid</option>
                                            
                                            <option value="2" {{$data->payment_status == 2 ? 'selected' :'' }}>Failed</option>
                                            <option value="3" {{$data->payment_status == 3 ? 'selected' :'' }}>Refund</option>
                                            <option value="0" {{$data->payment_status == 0 ? 'selected' :'' }}>Pending</option>
                                        </select>
                                    </td>
                                   <td> {{$data->transcation_id}}</td>
                                   <td> {{$data->total_price." ".$data->currency}}</td>
                                   <td> {{$data->created_at}}</td>
                                    <td class="row justify-content-center"> 
                                        <a href="{{ url('admin/bookings/'.$data->id.'/view') }}" class=" btn btn-primary"><i class="fa fa-eye"></i> </a>
                                     
                                    </td> 
                                </tr>
                                @endforeach
                            </tbody>
                            
                        </table>                  
                    </div>
                    <div class="col-md-12">
                    <div class=" float-right">
                
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