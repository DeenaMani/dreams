@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Gallery</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">

                    <h1>Add Gallery</h1>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/gallery') }}" method="post" enctype="multipart/form-data" class="row">
                            @csrf
                            <div class="col-lg-12">
                                <div class="row m-0">
                                    <div class="col-lg-6">
                                        <label for="type"> Gallery Type </label>
                                        <select class="form-select form-control mb-4"  name="type">
                                            <option selected disabled>Select Gallery Type</option>
                                            <option value="1">Photo</option>
                                            <option value="2">Video</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="year"> Gallery Year </label>
                                        <input type="text" class="form-control mb-4" name="year" value="">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="title"> Gallery title </label>
                                        <input type="text" class="form-control mb-4" name="title" value="">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="image"> Gallery Image </label>
                                        <input type="file" class="form-control mb-4" name="image" value="">
                                    </div>

                                    <div class="col-lg-12 video_link d-none">
                                        <label for="video_link"> Video Link </label>
                                        <input type="text" class="form-control mb-4" name="video_link" value="">
                                    </div>
          
                                    <div class="col-lg-12 my-5 pt-3" >
                                        <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Save Gallery">
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
    $("select[name='type']").change(function(){
        // Check the selected value of the dropdown
        if($(this).val() == 2) { // If "Video" is selected
            $(".video_link").removeClass("d-none"); // Show the video_link input
        } else {
            $(".video_link").addClass("d-none"); // Hide the video_link input
        }
    });
</script>

@endpush