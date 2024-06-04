@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add State</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add State</h1>
                    </div>
                    <div class="card-body text-dark">
                        @include('layouts.partials.messages')
                        <form action="{{ url('admin/state') }}" method="post" enctype="multipart/form-data" class="row">
                            @csrf

                            <div class="col-lg-12">

                                <div class="col-lg-6">
                                    <label for="country_id"> Country </label>
                                    <select class="form-select form-control mb-4"  name="country_id">
                                        <option selected disabled>Select Gallery Type</option>
                                        @foreach ($countries as $country)
                                        <option value="{{$country->id}}">{{$country->country_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label for="state_name"> State Name </label>
                                    <input type="Text" class="form-control mb-4"  name="state_name">
                                </div>
        
                                <div class="col-lg-6 my-5 pt-3" >
                                    <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Save state">
                                </div>  
                            </div>
                            
                        </form>
                    </div>
                </div> 
            </div>
        </div>
</div>
@endsection
        