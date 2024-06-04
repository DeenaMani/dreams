@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Teacher</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add Teacher</h1>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/instructor') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-lg-6">
                                    <label for="instructor_type"> Teacher Type </label> <br>
                                    <select class="form-control mb-4" name="instructor_type" required>                                
                                        <option disabled selected> Select Teacher Type </option>
                                        <option value="1">Teacher</option>
                                        <option value="2">Mentor</option>
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label for="instructor_image"> Teacher Image </label>
                                    <input type="file" class="form-control mb-3" name="instructor_image" required>
                                </div>  

                                <div class="col-lg-6">
                                    <label for="instructor_name"> Teacher Name </label>
                                    <input type="text" class="form-control mb-3" name="instructor_name" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="subject"> Subject </label>
                                    <input type="text" class="form-control mb-3" name="subject" required>
                                </div>

                                <div class="col-lg-6">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <label for="skills"> Skills </label>
                                            <input type="text" class="form-control mb-3" name="skills">
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="email"> E Mail </label>
                                            <input type="text" class="form-control mb-3" name="email" required>
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="phone">  Phone Number </label>
                                            <input type="text" class="form-control mb-3" name="phone" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="about">  About </label>
                                    <textarea type="text" class="form-control mb-3" name="about" rows="10"></textarea>
                                </div>
                               
                                <div class="col-lg-12 p-3 ">
                                    <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Save Teacher">
                                </div>
                            </div>                                   
                        </form>
                    </div>
                </div> 
            </div>
        </div>
</div>
@endsection
        