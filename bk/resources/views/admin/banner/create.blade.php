@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Banner</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">

                    <h1>Add Banner</h1>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/banner') }}" method="post" enctype="multipart/form-data" class="row">
                            @csrf

                            <div class="col-lg-12">
                                <div class="row">

                                <div class="col-lg-6">

                                    <div class="col-lg-12">
                                            <label for="banner_Image">Image </label>
                                            <input type="file" class="form-control mb-4" name="banner_image" rows="4"value="">
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="banner_title">Title </label>
                                            <textarea type="Text" class="form-control mb-4" name="banner_title" rows="4"value="" required></textarea>
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="banner_description">Description </label>
                                            <textarea type="Text" class="form-control mb-4" name="banner_description" rows="4"value=""></textarea>
                                        </div>

                                        <div class="col-lg-12 pt-4">
                                            <input type="submit" class="btn btn-primary text-white " value="Create Banner">
                                        </div> 
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
        