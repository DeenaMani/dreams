@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Edit Terms</h3>
                        </div>
                    </div>
                </div>
            </div>
       </div>

        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-header">
                        <h4>Edit Terms</h4>                   
                    </div>
                    <div class="card-body">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/terms/'.$terms->id) }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                            
                            <div class="col-lg-6">

                                <div class="col-lg-12">
                                    <div class="row">
                                        <label for="title"> Title</label>
                                        <input type="text" class="form-control mb-3" name="title" value="{{$terms->title}}" required>
                                    </div>
                                </div>

                                 <div class="col-lg-12">
                                    <div class="row">
                                        <label for="full_description"> terms Description </label>
                                        <textarea  class="form-control mb-3" id="summernote-basic" name="full_description" value="">{{$terms->full_description}}</textarea>
                                    </div>
                                </div>  
                               
                                <div class="row">
                                    <div class="col-lg-12 p-3 ">
                                        <input type="submit" class="btn btn-primary text-white" value="Update terms">
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