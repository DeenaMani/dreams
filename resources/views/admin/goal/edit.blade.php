@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold"> Edit Goal & Vision</h3>
                        </div>
                    </div>
                </div>
            </div>
       </div>
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-header">
                        <h4>Edit Goal & Vision</h4>                   
                    </div>
                    <div class="card-body">
                    @include('layouts.partials.messages')

                        <form action="{{ url('admin/goal/'.$goal->id) }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="image">Image </label>
                                        <input type="file" class="form-control mb-4" name="image" value="{{$goal->image}}">
                                        <img src="{{url('public/image/goal/'.$goal->image)}}" width="100px"> <br> 
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="title">Title </label>
                                        <input type="text" class="form-control mb-4" name="title" value="{{$goal->title}}">
                                    </div>

                                    <div class="col-lg-12">
                                        <label for="video_link">Video Link </label>
                                        <input type="text" class="form-control mb-4" name="video_link" value="{{$goal->video_link}}">
                                    </div>

                                    <div class="col-lg-12">
                                        <label for="banner_description">Description </label>
                                        <textarea type="text" class="form-control mb-4" id="summernote-basic" name="description" rows="4"value="">{{$goal->description}}</textarea>
                                    </div>

                                    <div class="col-lg-12 pt-4">
                                        <input type="submit" class="btn btn-primary text-white " value="Update Goal & Vision">
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