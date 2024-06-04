@extends('layouts.app')

@section('main-content')


<section class="course-details">
        <section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image.jpg');" >
            <div class="container">
                <div class="row content">
                    <div class="col-lg-8">
                        <h2 class="title">{{$course->course_name}}</h2>
                        <p class="content">{{$course->description}}
                            <!-- <div class="details">
                                <ul class="list-unstyled">
                                    <li><i class="fa fa-user pe-2"></i> 34,455 Enrolled</li>
                                    <li><div class="rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i> <span class="ps-2"> (3.0) </span>
                                    </div></li>
                                </ul>
                            </div> -->
                    </div>
                </div>
            </div>
        </section>
        <div class="description">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">



                       <div class="course-table">
                            <!-- <h3 class="course_title">Our Effort & Commitment Summary</h3> -->
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th scope="col" >Category</th>
                               
                                  <th scope="col">{{$course->course_name}}</th>
                                 

                                </tr>
                                <tr>
                                    <td>Number of Topics</td> 
                                    @php $topic_count = 0; @endphp 
                                   
                                     @php $topic_count +=$course->topic_count @endphp      
                                    <td>{{$course->topic_count}}</td> 
                                    
                                   
                                </tr>


                                <tr>
                                    <td>Number Of Recordings</td> 
                                    @php $recording_count = 0; @endphp 
                                   
                                     @php $recording_count +=$course->recording_count @endphp      
                                    <td>{{$course->recording_count}}</td> 
                                    
                                   
                                </tr>


                                <tr>
                                    <td>Approx Total Hours Of Recordings</td> 
                                    @php $total_hours_recordings = 0; @endphp 
                                   
                                     @php $total_hours_recordings +=$course->total_hours_recordings @endphp      
                                    <td>{{$course->total_hours_recordings}}</td> 
                                    
                                  
                                </tr>

                                <tr>
                                    <td>Live Session And Q&A</td> 
                                    @php $live_session = 0; @endphp 
                                   
                                     @php $live_session  = $course->live_session @endphp      
                                    <td>{{$course->live_session}}</td> 
                                    
                                    
                                </tr>

                                <tr>
                                    <td>Number Of Materials - Topic Wise</td> 
                                    @php $material_count = 0; @endphp 
                                   
                                     @php $material_count +=$course->material_count @endphp      
                                    <td>{{$course->material_count}}</td> 
                                    
                                    
                                </tr>


                                <tr>
                                    <td>Practice Tests - Chapter Wise (EASY/MEDIUM/COMPLEX)</td> 
                                    @php $practice_test_count = 0; @endphp 
                                   
                                     @php $practice_test_count +=$course->practice_test_count @endphp      
                                    <td>{{$course->practice_test_count}}</td> 
                                    
                                    
                                </tr>



                                <tr>
                                    <td>Grand Tests - Chapter Wise</td> 
                                    @php $grand_test_chapter = 0; @endphp 
                                   
                                     @php $grand_test_chapter +=$course->grand_test_chapter @endphp      
                                    <td>{{$course->grand_test_chapter}}</td> 
                                    
                                    
                                </tr>


                                <tr>
                                    <td>Grand Tests - Combining Few Chapters Together</td> 
                                    @php $topic_count = 0; @endphp 
                                   
                                     @php $topic_count +=$course->topic_count @endphp      
                                    <td>{{$course->topic_count}}</td> 
                                    
                                    
                                </tr>


                                <tr>
                                    <td>Grand Tests - Complete Syllabus (All Subjects)</td> 
                                    @php $grand_test_combine = 0; @endphp 
                                   
                                     @php $grand_test_combine +=$course->grand_test_combine @endphp      
                                    <td>{{$course->grand_test_combine}}</td> 
                                    
                                    
                                </tr>

                                <tr>
                                    <td>Course Presentation Language</td> 
                                    @php $language = 0; @endphp 
                                   
                                     @php $language =$course->language @endphp      
                                    <td>{{$course->language}}</td> 
                                    
                                    
                                </tr>

                                <tr>
                                    <td>Validity</td> 
                                    @php $validity = 0; @endphp 
                                   
                                    
                                    <td>{{days_convert($course->validity)}}</td> 
                                    
                                    
                                </tr>
                                
                             
                              </thead>
                              <tbody>
                              </tbody>
                            </table>


                            
                        <!-- <div class="text-center">
                        <a  href="contact.html" class="btn-theme-1">View More</a>
                        </div> --> 
                    </div>


                   
                @if($course)
                <h3 class=" mb-3 course_title">{{$course->course_name}}</h3>
               

                    @if($course->description)
                        <p>{!!$course->description!!}</p>
                    @endif

                  @php
                           $topics  = App\Models\Topic::where('course_id',$course->id)->where('type',1)->get();

                        @endphp


                       <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th scope="col" width="10%">#</th>
                                  <th scope="col">Topics</th>
   
                                </tr>
                              </thead>
                              <tbody>
                            @if(count($topics))
                                @foreach($topics as $key => $topic)  
                                <tr>
                                  <th scope="row">{{$key+ 1}}</th>
                                  <td>{{$topic->topic}}</td>
                                </tr>
                                @endforeach
                            @else
                            <tr><th colspan="2" class="text-center-=1">Coming Soon</th></tr>
                            @endif

                                
                              </tbody>
                            </table>
                   
               
                @endif

                  
                
                        <div class="overview border-bottom">
                            <div class="title h3 mb-3">About The Course</div>
                             {!!$course->full_description!!}
                        </div>
                       
                       
                    </div>
                    <div class="col-lg-4">
                        <aside class="sidebar">
                            <div class="course-specification">
                                <div class="iamge">
                                    <img src="{{assets()}}/images/cources/course2.jpg">
                                </div>

                                <div class="course-spec-details">
                                    <div class="price">
                                        {{get_price($course->price)}}  <del>{{get_price($course->price)}} </del>
                                        <!-- <span class="offer">13% OFF</span> -->
                                    </div>
                                    <div class="add-to-cart">
                                       <a class="btn-theme-1 add_to_cart" data-id="{{$course->id}}" data-type="2" href="javascript:void(0)"  > <i class="fa fa-cart-shopping pe-3"></i> Add To Cart</a>
                                    </div>

                                    
                                     
                                    </div>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
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
            $(".cart-count").html(data.totalCount);
           // console.log(data);
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