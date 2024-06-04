@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Event</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add Event</h1>
                    </div>
                    <div class="card-body text-dark">
                        @include('layouts.partials.messages')
                        <form action="{{ url('admin/event') }}" method="post" enctype="multipart/form-data" class="row">
                            @csrf

                            <div class="col-lg-12">
                                <div class="row">

                                    <div class="col-lg-6">
                                        <label for="type"> Event Type </label>
                                        <select class="form-select form-control mb-4"  name="type">
                                            <option selected disabled>Select Event Type</option>
                                            <option value="1">Onlie</option>
                                            <option value="2">Offline</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="title"> Event Title </label>
                                        <input type="Text" class="form-control mb-4"  name="title">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="date"> Event Date </label>
                                        <input type="date" class="form-control mb-4"  name="date">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="meeting_link"> Event Link </label>
                                        <input type="link" class="form-control mb-4"  name="meeting_link">
                                    </div>

                                    <div class="col-lg-12">
                                        <label for="description"> Description </label>
                                        <textarea type="Text" id="summernote-basic" class="form-control mb-4"  name="description"> </textarea>
                                    </div>
          
                                    <div class="col-lg-12 my-5 pt-3" >
                                        <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Save Event">
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
        