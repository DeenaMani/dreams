@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h2 class="mb-1 font-weight-bold text-white">About</h2>
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
                        <h4>About</h4> 
                        </div>

                       <!--  <div class="col-lg-3">
                            <a href="{{url('admin/about/create')}}" class="btn btn-primary me-5" style="float:right;"> Add <i class="bx bx-plus"></i></a> 
                        </div> -->
                    </div>                
                 
                    <div class="card-body">

                         @include('layouts.partials.messages')
                        <table class="table text-center mb-0">
                            <thead>
                                <tr>
                                    <th> Id </th>
                                    <th> Title </th>
                                  
                                    <th> Action </th>
                                </tr>
                            </thead>
                        
                            <tbody>
                                @foreach ($result as $key => $data)
                                <tr>
                                    <td> {{$data->id}} </td>
                                    <td> {{$data->title}} </td>
                                    
                                    <td class="row justify-content-center"> 
                                        <a href="{{ url('admin/about/'.$data->id.'/edit') }}" class=" btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                        <!--  <form action="{{ url('admin/about/'.$data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mx-3 btn btn-danger" onclick="return confirm('Are you sure to delete ?')"  ><i class="fa-solid fa-trash"></i></button> 
                                        </form>  -->
                                    </td> 
                                </tr>
                                @endforeach
                            </tbody>
                        
                        </table>                  
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
                url: "{{URL('/')}}/admin/about/status/" + id + "/" +status ,
                    success: function(e) {
                }
            });
        });
    </script>
@endpush