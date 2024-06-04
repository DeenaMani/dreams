@extends('layouts.admin')

@push('css')
<style>
    form img {
        background-color: #0495FF;
        padding: 10px;
        border-radius: 5px;
    }
</style>
@endpush

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Our Values</h3>
                        </div>
                    </div>
                </div>
            </div>
       </div>
        @include('layouts.partials.messages')
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-header">
                        <h4>Edit Our Values</h4>                   
                    </div>
                    <div class="card-body">

                        <form action="{{ url('admin/our-values/'.$result->id) }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')

                                <div class="row">

                                    <div class="col-lg-6">
                                        <label for="Image">Image </label>
                                        <input type="file" class="form-control mb-4" name="image" rows="4" value="{{$result->image}}">
                                        <img src="{{url('public/image/our-values/'.$result->image)}}" width="50px"> <br> 
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="title">Title </label>
                                        <input type="Text" class="form-control mb-4" name="title" value="{{$result->title}}" required>
                                    </div>

                                    <div class="col-lg-12">
                                        <label for="description">Description </label>
                                        <textarea type="Text" class="form-control mb-4" name="description" id="summernote-basic" value="{{$result->description}}">{{$result->description}}</textarea>
                                    </div>

                                    <div class="col-lg-12 pt-4">
                                        <input type="submit" class="btn btn-primary text-white " value="Update our-values">
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