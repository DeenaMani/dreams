@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Faqs</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add Faq</h1>
                    </div>
                    <div class="card-body text-dark">
                        @include('layouts.partials.messages')
                        <form action="{{ url('admin/faq') }}" method="post" enctype="multipart/form-data" class="row">
                            @csrf
                            <div class="col-lg-6">

                                <div class="col-lg-12">
                                    <div class="row">
                                        <label for="faq_title"> Title </label>
                                        <input type="text" class="form-control mb-4"  name="faq_title" required> 
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="row">
                                        <label for="faq_description"> Description </label>
                                        <textarea type="text" class="form-control mb-4" id="summernote-basic"  name="faq_description" required></textarea> 
                                    </div>
                                </div>
              
                                <div class="col-lg-12  p-3">
                                    <input type="submit" class="btn btn-primary text-white" style="float:left;"  value="Save faq">
                                </div>
                                   
                            </div>    
                        </form>
                    </div>
                </div> 
            </div>
        </div>
</div>
@endsection
        