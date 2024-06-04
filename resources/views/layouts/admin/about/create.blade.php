@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add About</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add About</h1>
                    </div>
                    <div class="card-body text-dark">
                        @include('layouts.partials.messages')
                        <form action="{{ url('admin/about') }}" method="post" enctype="multipart/form-data" class="row">
                            @csrf

                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="title"> Title </label>
                                        <input type="Text" class="form-control mb-4"  name="title">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="image"> Image </label>
                                        <input type="file" class="form-control mb-4"  name="image">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="who_we_are"> Who We Are </label>
                                        <textarea type="Text" class="form-control mb-4" rows="3"  name="who_we_are"> </textarea>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="what_we_do"> What We Do </label>
                                        <textarea type="Text" class="form-control mb-4" rows="3"  name="what_we_do"> </textarea>
                                    </div>
                                    
                                    <div class="col-lg-12">
                                        <label for="about_description"> Description </label>
                                        <textarea type="Text" id="summernote-basic" class="form-control mb-4"  name="about_description"> </textarea>
                                    </div>
          
                                    <div class="col-lg-12 my-5 pt-3" >
                                        <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Save about">
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
        