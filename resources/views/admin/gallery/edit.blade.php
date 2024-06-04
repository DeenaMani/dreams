@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold"> Edit Gallery</h3>
                        </div>
                    </div>
                </div>
            </div>
       </div>
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-header">
                        <h4>Edit Gallery</h4>                   
                    </div>
                    <div class="card-body">
                    @include('layouts.partials.messages')

                        <form action="{{ url('admin/gallery/'.$gallery->id) }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                            <div class="col-lg-12">
                                <div class="row m-0">
                                    <div class="col-lg-6">
                                        <label for="type"> Gallery Type </label>
                                        <select class="form-select form-control mb-4"  name="type">
                                            <option selected disabled>Select Gallery Type</option>
                                            <option value="1" {{$gallery->type == '1' ? 'selected' : ''}}>Photo</option>
                                            <option value="2" {{$gallery->type == '2' ? 'selected' : ''}}>Video</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="year"> Gallery Year </label>
                                        <input type="text" class="form-control mb-4" name="year" value="{{$gallery->year}}">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="title"> Gallery title </label>
                                        <input type="text" class="form-control mb-4" name="title" value="{{$gallery->title}}">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="image"> Gallery Image </label>
                                        <input type="file" class="form-control mb-4" name="image" value="{{$gallery->image}}">
                                        <img src="{{url('public/image/gallery/'.$gallery->image)}}" width="100px"> <br> 
                                    </div>

                                    <div class="col-lg-12 video_link d-none">
                                        <label for="video_link"> Video Link </label>
                                        <input type="text" class="form-control mb-4" name="video_link" value="{{$gallery->video_link}}">
                                    </div>
          
                                    <div class="col-lg-12 my-5 pt-3" >
                                        <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Update Gallery">
                                    </div>  
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
    $(document).ready(function(){
        
        if($("select[name='type']").val() == 2) {
            $(".video_link").removeClass("d-none"); 
        } else {
            $(".video_link").addClass("d-none"); 
        }
       
        $("select[name='type']").change(function(){
            
            if($(this).val() == 2) { 
                $(".video_link").removeClass("d-none"); 
            } else {
                $(".video_link").addClass("d-none");
            }
        });
    });
</script>

@endpush