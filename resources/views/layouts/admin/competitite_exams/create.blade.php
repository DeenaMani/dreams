@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Competitive Exam</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add Competitive Exam</h1>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/competitite-exam') }}" method="post" enctype="multipart/form-data">
                            @csrf
                                <div class="row">
                                    <div class="col-lg-6">              
                                        <label for="exam_name"> Exam Name </label>
                                        <input type="text" class="form-control mb-3" name="exam_name" value="{{old('exam_name')}}" required>
                                    </div>

                                    <div class="col-lg-6">              
                                        <label for="exam_name">Image </label>
                                        <input type="file" class="form-control mb-3" name="image_name" required>
                                    </div>

                                        <div class="col-lg-12">
                                        <label for="description"> Exam Short Description</label>
                                        <textarea  class="form-control mb-3" name="short_description" id="summernote-basic-1" required>{{old('short_description')}}</textarea>
                                    </div>  

                                    <div class="col-lg-12">
                                        <label for="description"> Exam Description </label>
                                        <textarea  class="form-control mb-3" name="description" id="summernote-basic" required>{{old('description')}}</textarea>
                                    </div>                                
                                    
                                    <div class="col-lg-12 p-3 ">
                                        <input type="submit" class="btn btn-primary text-white" value="Save Competitive Exam">
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