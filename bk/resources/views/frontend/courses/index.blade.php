@extends('layouts.app')

@section('main-content')
<style type="text/css">.notifyjs-corner{     z-index: 100000000 !important; } </style>
<section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image-2.jpg');">
            <div class="container">
                <div class="row content">
                    <h1>Courses For You Children's</h1>
                </div>
            </div>
        </section>

        <section class="courses-page">
            <div class="container">
                <!-- <div class="row filter align-items-center">
                    <div class="col-lg-4 col-md-6 col-md-4">
                        <div class="text-muted h6"> We Found 16 Courses For You</div>
                    </div>
                    <div class=" col-lg-8 col-md-6 row m-0">
                        <ul class="list-unstyled text-md-end text-start mb-0">
                            <li class=" d-inline-block">
                                <select name="sort-by" class="form-control">
                                    <option disabled selected>Sort BY</option>
                                    <option value="">Latest</option>
                                    <option value="">Name &#160; A to Z</option>
                                    <option value="">Name &#160; Z to A</option>
                                    <option value="">Price &#160; Low to High</option>
                                    <option value="">Price &#160; High to Low</option>
                                </select>
                            </li>
                           <li class=" d-inline-block ps-3">
                                <div class="d-inline-block filter-btn" data-bs-toggle="offcanvas" data-bs-target="#filter"><i class="fa fa-filter pe-3"></i>Filter</div>
                            </li>
                        </ul>
                    </div>
                </div> -->
                @if($results)
                @foreach($results as $row)
                <div class="course-list mt-5">
                    <h3 class="title"> <span>{{$row->category_name}} {{$row->id}}</span> </h3>
                    <div class="row">

                        @php
                            $courses = $row->course;
                            $slug = $row->category_slug;


                        @endphp
                        @if(count($courses) >  0)
                    
                        @foreach($courses as $course)
                        @php 
                            if($row->is_mutiple  == 0) $slug = $course->slug; 

                        @endphp
                        <div class="col-xl-3 col-lg-4 col-sm-6 col-12 mb-3">
                            <div class="course-box">
                                <div class="img">
                                    <a href="{{url('courses/'.$slug.'/'.$course->slug)}}"><img src="{{assets()}}image/course/{{$course->course_image}}"></a>
                                </div>
                                <div class="content">
                                    <div class="course-name h6"><a href="{{url('courses/'.$slug.'/'.$course->slug)}}">{{$course->course_name}}</a>


                                    </div>
                                    <p class="text-center"> <a href="{{url('courses/'.$slug.'/'.$course->slug)}}">Read More</a></p>

                                    @if($row->is_mutiple  == 0)
                                    <div class="enroll">
                                        <div class="row align-items-center">
                                            <div class="col-lg-6 col-sm-6 col-6 pe-0">
                                                <div class="price d-inline-block">₹ 1300 <del class="text-muted d-inline-block"> ₹ 1500</del></div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-6 text-end">
                                                <a href="javascript:void(0)" class="btn-enroll rounded-2">Add To Cart</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif


                                </div>
                            </div>
                        </div>

                        @endforeach
                       
                       
                        @if($row->is_mutiple  == 1)

                       <div class="course-table">
                            <!-- <h3 class="course_title">Our Effort & Commitment Summary</h3> -->
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th width="200px" scope="col" width="10%">Category</th>
                                   @foreach($courses as $key => $course)
                                  <th scope="col">{{$course->course_name}}</th>
                                  @endforeach
                                  <th scope="col">TOTAL</th>

   
                                </tr>
                                <tr>
                                    <td>Number of Topics</td> 
                                    @php $topic_count = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                     @php $topic_count +=$course->topic_count @endphp      
                                    <td>{{$course->topic_count}}</td> 
                                     @endforeach
                                    <td>{{$topic_count}}</td>
                                </tr>


                                <tr>
                                    <td>Number Of Recordings</td> 
                                    @php $recording_count = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                     @php $recording_count +=$course->recording_count @endphp      
                                    <td>{{$course->recording_count}}</td> 
                                     @endforeach
                                    <td>{{$recording_count}}</td>
                                </tr>


                                <tr>
                                    <td>Approx Total Hours Of Recordings</td> 
                                    @php $total_hours_recordings = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                     @php $total_hours_recordings +=$course->total_hours_recordings @endphp      
                                    <td>{{$course->total_hours_recordings}}</td> 
                                     @endforeach
                                    <td>{{$total_hours_recordings}}</td>
                                </tr>

                                <tr>
                                    <td>Live Session And Q&A</td> 
                                    @php $live_session = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                     @php $live_session  = $course->live_session @endphp      
                                    <td>{{$course->live_session}}</td> 
                                     @endforeach
                                    <td>-</td>
                                </tr>

                                <tr>
                                    <td>Number Of Materials - Topic Wise</td> 
                                    @php $material_count = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                     @php $material_count +=$course->material_count @endphp      
                                    <td>{{$course->material_count}}</td> 
                                     @endforeach
                                    <td>{{$material_count}}</td>
                                </tr>


                                <tr>
                                    <td>Practice Tests - Chapter Wise (EASY/MEDIUM/COMPLEX)</td> 
                                    @php $practice_test_count = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                     @php $practice_test_count +=$course->practice_test_count @endphp      
                                    <td>{{$course->practice_test_count}}</td> 
                                     @endforeach
                                    <td>{{$practice_test_count}}</td>
                                </tr>



                                <tr>
                                    <td>Grand Tests - Chapter Wise</td> 
                                    @php $grand_test_chapter = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                     @php $grand_test_chapter +=$course->grand_test_chapter @endphp      
                                    <td>{{$course->grand_test_chapter}}</td> 
                                     @endforeach
                                    <td>{{$grand_test_chapter}}</td>
                                </tr>


                                <tr>
                                    <td>Grand Tests - Combining Few Chapters Together</td> 
                                    @php $topic_count = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                     @php $topic_count +=$course->topic_count @endphp      
                                    <td>{{$course->topic_count}}</td> 
                                     @endforeach
                                    <td>{{$topic_count}}</td>
                                </tr>


                                <tr>
                                    <td>Grand Tests - Complete Syllabus (All Subjects)</td> 
                                    @php $grand_test_combine = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                     @php $grand_test_combine +=$course->grand_test_combine @endphp      
                                    <td>{{$course->grand_test_combine}}</td> 
                                     @endforeach
                                    <td>{{$grand_test_combine}}</td>
                                </tr>

                                <tr>
                                    <td>Course Presentation Language</td> 
                                    @php $language = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                     @php $language =$course->language @endphp      
                                    <td>{{$course->language}}</td> 
                                     @endforeach
                                    <td></td>
                                </tr>

                                <tr>
                                    <td>Validity</td> 
                                    @php $validity = 0; @endphp 
                                     @foreach($courses as $key => $course)  
                                    
                                    <td>{{$course->validity}} Days</td> 
                                     @endforeach
                                    <td>-</td>
                                </tr>
                                
                             
                              </thead>
                              <tbody>
                              </tbody>
                            </table>


                            
                    <!-- <div class="text-center">
                    <a  href="contact.html" class="btn-theme-1">View More</a>
                    </div> --> 
                </div>
                <a class="btn-theme-1 d-block text-center rounded-0 add_to_cart" data-id="{{$row->id}}" data-type="1" href="javascript:void(0)">
                                <price Class="pe-3"> {{get_price($row->price)}}/- <del class="ps-2">{{get_price($row->old_price)}}/-</del></price>
                                 Add To Cart
                            </a>
                            @endif
                        @else
                        <h5 class=" mb-3">Coming Soon</h5>

                     @endif
                @endforeach
                @endif

                @if($row->is_mutiple  == 1)
                    <a class="btn-theme-1 d-block text-center rounded-0 add_to_cart" data-id="{{$row->id}}" data-type="1" href="javascript:void(0)">
                        <price Class="pe-3"> {{get_price($row->price)}}/- <del class="ps-2">{{get_price($row->old_price)}}/-</del></price>
                        Add To Cart
                    </a>
                @endif


            </div>
        </section>
@endsection

@push('scripts')
<script>
    $(".add_to_cart").on("click",function(){

        var id = $(this).data('id');
        var type = $(this).data('type');
        $.ajax({
           type:'POST',
           url:'{{route("addToCart")}}',
           dataType: 'json',
           data: {'_token' : '{{csrf_token()}}' ,  type : type , id : id },
           success:function(data) {
            $(".cart-count").html(data.totalCount)
                if(data.status == 1){
                    $.notify(data.message, "success");
                }   
                else{
                    $.notify(data.message, "warn");
                }
              
           }
        });
    });
</script>
@endpush