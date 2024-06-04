@extends('layouts.admin')

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Edit Course</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Edit Course</h1>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/course/'.$course->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">

                                <div class="col-lg-6">
                                    <label for="Category_id"> Category </label> <br>
                                    <select  class="form-control mb-4" name="category_id" required>                               
                                        <option disabled> Select Category </option>
                                            @foreach($categories as $category)
                                            <option  value="{{$category->id}}" {{ $course->category_id == $category->id ? 'selected' : '' }}>{{$category->category_name}}</option>
                                            @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label for="image"> Course Image </label>
                                    <input type="file" class="form-control mb-3" name="course_image" >
                                </div>  

                                <div class="col-lg-6">
                                    <label for="course_name"> Course Name </label>
                                    <input type="text" class="form-control mb-3" name="course_name" placeholder="Course Name"  value="{{old('course_name',$course->course_name)}}" required>
                                </div>

                                  <div class="col-lg-6 mutiple_is_div"  style="display: none;">
                                    <label for="offered_price"> Old Price </label>
                                    <input type="text" class="form-control mb-3" name="offered_price"  value="{{old('offered_price',$course->offered_price)}}">
                                </div>

                                <div class="col-lg-6 mutiple_is_div"  style="display: none;">
                                    <label for="price"> Course Price </label>
                                    <input type="text" class="form-control mb-3" name="price" placeholder="Course Price" value="{{old('price',$course->price)}}">
                                </div>

                              


                                <div class="col-lg-6">
                                    <label for="instructors_id[]"> Instructors </label> <br>
                                    <select class="form-control form-select select2" name="instructor_id[]"  multiple required>                             
                                             @foreach($instructors as $instructor)
                                                @php
                                                    $selected = in_array($instructor->id, json_decode($course->instructor_id ?? '[]')) ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $instructor->id }}" {{ $selected }}>{{ $instructor->instructor_name }}</option>
                                            @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label for="duration"> Recording Duration </label>
                                    <input type="text" class="form-control mb-3" placeholder="Recording Duration" name="duration" value="{{old('duration',$course->duration)}}"  required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="topic_count"> Number Of Topics </label>
                                    <input type="number" class="form-control mb-3" name="topic_count" placeholder="Number Of Topics" value="{{old('topic_count',$course->topic_count)}}" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="recording_count"> Number Of Recordings</label>
                                    <input type="number" class="form-control mb-3" name="recording_count" placeholder="Number Of Recordings" value="{{old('recording_count',$course->recording_count)}}"  required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="total_hours_recordings"> Approx Total Hours Of Recordings</label>
                                    <input type="text" class="form-control mb-3" name="total_hours_recordings" placeholder=" Approx Total Hours Of Recordings" value="{{old('total_hours_recordings',$course->total_hours_recordings)}}"  required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="live_session"> Live Session And  Q&A </label>
                                    <input type="text" class="form-control mb-3" placeholder=" Live Session And  Q&A"  name="live_session" value="{{old('live_session',$course->live_session)}}" required>
                                </div>


                                <div class="col-lg-6">
                                    <label for="material_count"> Number Of Materials - Topic Wise</label>
                                    <input type="number" class="form-control mb-3" placeholder="Number Of Materials - Topic wise" value="{{old('material_count',$course->material_count)}}"  name="material_count" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="practice_test_count">Practice Tests - Chapter Wise (EASY/MEDIUM/COMPLEX)</label>
                                    <input type="number" class="form-control mb-3" placeholder="Practice Tests - Chapter Wise" value="{{old('practice_test_count',$course->practice_test_count)}}" name="practice_test_count" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="grand_test_chapter">Grand Tests - Chapter Wise </label>
                                    <input type="number" class="form-control mb-3" placeholder="Grand Tests - Chapter Wise" name="grand_test_chapter" value="{{old('grand_test_chapter',$course->grand_test_chapter)}}"  required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="grand_test_combine"> Grand Tests - Combining Few Chapters Together </label>
                                    <input type="number" class="form-control mb-3" placeholder="Grand Tests - Combining Few Chapters Together" value="{{old('grand_test_combine',$course->grand_test_combine)}}" name="grand_test_combine"  value="{{old('grand_test_combine',$course->grand_test_combine)}}" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="grand_test_syllabus"> Grand Tests - Complete Syllabus (All Subjects)</label>
                                    <input type="number" placeholder="Grand Tests - Complete Syllabus (All Subjects)" class="form-control mb-3" name="grand_test_syllabus"  value="{{old('grand_test_syllabus',$course->grand_test_syllabus)}}" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="language"> Course Presentation Language </label>
                                    <input type="text" placeholder="Course Presentation Language" class="form-control mb-3" name="language" value="{{old('language',$course->language)}}" required>
                                </div>

                                <div class="col-lg-6">
                                    <label for="validity"> Validity </label>
                                    <select class="form-control mb-4" name="validity" required>                                
                                        <option disabled selected> Select Validity Time </option>
                                            <option value="30" {{$course->validity == '30' ? 'selected' : ''}}>1 Month</option>
                                            <option value="90" {{$course->validity == '90' ? 'selected' : ''}}>3 Month</option>
                                            <option value="180" {{$course->validity == '180' ? 'selected' : ''}}>6 Month</option>
                                            <option value="360" {{$course->validity == '360 '? 'selected' : ''}} >1 Year</option>
                                            <option value="720" {{$course->validity == '720' ? 'selected' : ''}}>2 year</option>
                                            <option value="1800" {{$course->validity == '1800' ? 'selected' : ''}}>5 year</option>
                                    </select>
                                </div>

                                

                                <div class="col-lg-12">
                                    <label for="description">Short Description </label>
                                    <textarea type="textarea" class="form-control mb-3" rows="5" name="description">{{old('description',$course->description)}}</textarea>
                                </div>



                                <div class="col-lg-12">
                                    <label for="full_description"> Full Description </label>
                                    <textarea type="textarea" class="form-control mb-3" id="summernote-basic" rows="5" name="full_description">{{old('full_description',$course->full_description)}}</textarea>
                                </div>
                                

                                <div class="col-lg-12 p-3 ">
                                    <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Save course">
                                </div>
                            </div>                                   
                        </form>
                    </div>
                </div> 
            </div>
        </div>
</div>
@endsection
        
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script type="text/javascript">
    $(".category").change(function(){
        var multiple=  $(this).find(':selected').attr('data-multiple');
        if( multiple  == 1){
                $(".mutiple_is_div").hide();
                 $(".mutiple_is_div input").val("");
        }
        else{
             $(".mutiple_is_div").show();
        }
    });
</script>
@endpush