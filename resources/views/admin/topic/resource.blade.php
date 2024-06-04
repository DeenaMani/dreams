@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Topic</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add Topic</h1>
                    </div>
                    <div class="card-body text-dark">
                        @include('layouts.partials.messages')
                        
                            <form action="{{ url('admin/resource_store') }}" method="post"  class="border-bottom mb-4" enctype="multipart/form-data">
                                @csrf
                                <input type="text" class="form-control mb-3" name="topic_id" value="{{$topic->id}}" hidden>
                                <div class="row">
                        <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="topic"> Topic </label>
                                        <input type="text" class="form-control mb-3" name="" value="{{$topic->topic}}" disabled readonly>
                                    </div>

                                    <div class="col-lg-12">
                                        <label for="title"> Title </label>
                                        <input type="text" class="form-control mb-3" value="{{old('title')}}" name="title" required>
                                    </div>  
                                   
                                    @if($topic->type == 1)
                                    <div class="col-lg-12">
                                        <label for="video_link"> Video Link</label>
                                        <input type="text" class="form-control mb-3" name="video_link" value="{{old('video_link')}}" required>
                                    </div>  
                                    @endif
                                    @if($topic->type == 2)
                                    <div class="col-lg-12">
                                        <label for="pdf"> Topic Pdf </label>
                                        <input type="file" class="form-control mb-3" name="pdf" required>
                                    </div> 
                                    @endif

                                   <div class="col-lg-12 mt-5">
                                        <div class="row">
                                            <label for="is_free"> Free </label>
                                          <div class="form-check form-switch">
                                                <input class="toggle-class" type="checkbox" name="is_free" value="1" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" 
                                                data-on="Active" data-off="InActive" >
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-lg-12 p-3 ">
                                        <input type="submit" class="btn btn-primary text-white" style="float:right; " value="Save">
                                    </div>
                                </div> 
                                 </div>
                    </div>                                  
                            </form>
                       

                        <table id="datatable" class="table border-top">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                     @if($topic->type == 1)
                                    <th>Video Link</th>
                                    @endif
                                     @if($topic->type == 2)
                                    <th>PDF</th>
                                    
                                    @endif
                                    <th>Free Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $key => $data)

                                <tr>
                                    <td> {{$data->title}} </td>
                                     @if($topic->type == 1)
                                    <td> <a href="{{$data->video_link}}" target="_blank">{{$data->video_link}}</td>
                                         @endif
                                     @if($topic->type == 2)
                                    <td> <a href="{{$data->pdf}}" target="_blank">{{$data->pdf}}</td>
                                        @endif
                                    <td>
                                        <input value="{{$data->id}}" class="toggle-class is_free" type="checkbox" name="is_free" value="1" data-onstyle="success" data-offstyle="danger" data-toggle="toggle"  data-on="Active" data-off="InActive" {{$data->is_free ?  "checked" : ""}} >

                                    </td>
                                    <td class="row justify-content-center"> 
                                        <form action="{{ url('admin/delete-resource/'.$data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mx-1 btn btn-danger" onclick="return confirm('Are you sure to delete ?')" ><i class="fa fa-trash"></i></button> 
                                        </form>
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

<script>
    $('.resource_type').change(function() {
        var type = $(this).val();

        if(type == 1){
            $(".duration_div").show();
        }
        else if(type == 2){
            $(".duration_div").hide();
        }
        
    });

      $(document).on("change",".is_free",function() {
   
         if($(this).is(":checked")) { var status = 1;}
         else{   var status = 0;  }  
         var id= $(this).val()
         //alert(id);
         $.ajax({
             url: "{{URL('/')}}/admin/topic/resource-status/" + id + "/" +status ,
                 success: function(e) {
             }
         });
     });

</script>

@endpush
        