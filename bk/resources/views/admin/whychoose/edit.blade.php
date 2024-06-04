@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Why Choose</h3>
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
                        <h4>Edit Why Choose</h4>                   
                    </div>
                    <div class="card-body">

                        <form action="{{ url('admin/whychoose/'.$whychoose->id) }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                            <div class="col-lg-12">
                                <div class="row">

                                    <div class="col-lg-6">

                                        <div class="col-lg-12">
                                            <label for="whychoose_Image">Image </label>
                                            <input type="file" class="form-control mb-4" name="whychoose_image"value="{{$whychoose->whychoose_image}}">
                                            <img src="/image/whychoose/{{$whychoose->whychoose_image}}" width="50px"> <br> 
                                        </div>

                                        <!-- <div class="col-lg-12">
                                            <label for="whychoose_title">Title </label>
                                            <input type="Text" class="form-control mb-4" name="whychoose_title"value="{{$whychoose->whychoose_title}}" >
                                        </div> -->

                                    <!-- </div>
                                    
                                    <div class="col-lg-6"> -->

                                        <div class="col-lg-12">
                                            <label for="whychoose_description">Description </label>
                                            <textarea type="Text" id="summernote-basic" class="form-control mb-4" name="whychoose_description" rows="4" value="{{$whychoose->whychoose_description}}">{{$whychoose->whychoose_description}}</textarea>
                                        </div>

                                        <div class="col-lg-12 pt-4">
                                            <input type="submit" class="btn btn-primary text-white"  style="float:right;" value="Update whychoose">
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