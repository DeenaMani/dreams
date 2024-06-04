@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Edit Topics</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Edit Topics</h1>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                         <form action="{{ url('admin/topic/'.$result->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="col-lg-6">
                                    <label for="Category_id"> Course </label> <br>
                                    <select name="course_id" id="course_id" class="form-control  mb-4" required>                               
                                        <option  value=""> Select Course </option>
                                            @foreach($courses as $course)
                                            <option value="{{$course->id}}" {{$result->course_id ==  $course->id ? "selected" :"" }}>{{$course->category_name}}  -  {{$course->course_name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-lg-6">
                                    <label for="topic"> Topic  Name</label> 
                                    <input type="text" class="form-control mb-3" value="{{old('topic',$result->topic)}}" name="topic" required>
                                </div>  
                            </div>

                             <div class="row">

                             <div class="col-lg-6">
                                    <label for=" resource_type"> Resource Type </label>
                                    <select class="form-control mb-4 resource_type" name="resource_type" required>    
                                            <option  value=""> Select Resource </option>                            
                                            <option value="1" {{$result->type == 1 ? "selected" :"" }}  >Video's</option>
                                            <option value="2" {{$result->type == 2 ? "selected" :"" }}>Study Material's</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row duration_div" style="display: {{$result->type == 2 ? 'none':''}}">
                                
                                <div class="col-lg-6">
                                    <label for="duration"> Duration </label>
                                    <input type="text" class="form-control mb-3" value="{{old('duration',$result->duration)}}" name="duration" >
                                </div>  
                            </div>
                             
                               
                               
                                <div class="col-lg-6 p-3 ">
                                    <input type="submit" class="btn btn-primary text-white" style="float:right; " value="Save">
                                </div>
                            </div>                                   
                        </form>
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
</script>

@endpush
        