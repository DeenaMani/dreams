@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h2 class="mb-1 font-weight-bold text-white"> Purchase History </h2>
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
                        <h4> Purchase History </h4> 
                        </div>

                        <div class="col-lg-3">
                            <a href="{{url('admin/competitite-exam/create')}}" class="btn btn-primary me-5" style="float:right;"> Add <i class="bx bx-plus"></i></a>
                        </div>
                    </div>                
                 
                    <div class="card-body">
                         @include('layouts.partials.messages')
                         
                        <table class="table text-center mb-0" id="datatable">
                            <thead>
                                <tr>
                                    <th> Id </th>
                                    <th> Purchase No </th>
                                    <th> Student</th>
                                    <th> Payment Method</th>
                                    <th> Total</th>
                                    <th> Date & Time </th>
                                    <th> Status </th>
                                    <th> Action </th>
                            </thead>
                            
                            <tbody>
                                @foreach ($results as $key => $data)
                                <tr>
                                    <td> {{$data->id}} </td>
                                    <td> {{$data->purchase_no}} </td>
                                    <td> {{$data->first_name}} &nbsp;  {{$data->last_name}} </td>
                                    <td> {{$data->payment_method}} </td>
                                    <td> {{$data->currency}} {{$data->total_price}} </td>
                                    <td> {{$data->created_at}} </td>
                                    <td> 
                                        <!-- <div class="form-check form-switch">
                                            <input data-id="{{$data->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" 
                                            data-on="Active" data-off="InActive" {{ $data->status ? 'checked' : '' }}>
                                        </div> -->
                                        {{$data->status}}
                                    </td>
                                    <td class="row justify-content-center"> 
                                        <a href="{{ url('admin/purchse/'.$data->id.'/edit') }}" target="_blank" class=" btn btn-primary"><i class="fa fa-file"></i> </a>
                                        <form action="{{ url('admin/competitite-exam/'.$data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mx-3 btn btn-danger" onclick="return confirm('Are you sure to delete ?')" ><i class="fa fa-trash"></i></button> 
                                        </form>
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
    
     $(document).on("change",".toggle-class",function() {
   
         if($(this).is(":checked")) { var status = 1;}
         else{   var status = 0;  }  
         var id= $(this).attr("data-id")
         //alert(id);
         $.ajax({
             url: "{{URL('/')}}/admin/competitite-exam/status/" + id + "/" +status ,
                 success: function(e) {
             }
         });
     });

    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#datatable')) {
            $('#datatable').DataTable().destroy();
        }
        
        $('#datatable').DataTable({
            "order": [[0, 'desc']] 
        });
    });
   </script>
     @endpush