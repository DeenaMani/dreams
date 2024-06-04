@extends('layouts.app')

@section('main-content')
@if(count($banners))
    <section class="banner">
        <div class="slider">
            <div class="owl-carousel banner-carousel">
                @foreach($banners as $banner)
                <div class="banner" style="background-image:url('{{assets()}}image/banner/{{$banner->banner_image}}');">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="content col-lg-8 text-center">
                                <h1 class="title">{{$banner->banner_title}}</h1>
                                <div class="p">{{$banner->banner_description}}</div>
                                <div class="mt-4">
                                    <a  href="{{url('book-session')}}" class="btn-theme-1"> Get Free Session</a> 
                                    <a  href="{{url('courses')}}" class="btn-theme-2 ms-4"> View All Course</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
 @endif
    <!-- <section class="categories">
        <div class="container">
            <h2 class="text-center head"><span>Popular Categories</span></h2>
            <div class="col-lg-12 m-auto">
                <div class="slider">
                    <div class="owl-carousel categories-carousel">

                        <a href="">
                        <div class="course-box">
                          
                            <div class="logo">
                                <img src="{{assets()}}/images/cources/course2.png">
                            </div>
                            <div class="course-name">6th Grade SAINIK/RMS/RIMS</div>
                        </div>
                        </a>

                        <a href="">
                        <div class="course-box">
                           
                            <div class="logo">
                                <img src="{{assets()}}/images/cources/course1.png">
                            </div>
                            <div class="course-name">9th Grade SAINIK/RMS/RIMS </div>
                        </div>
                        </a>

                                    <a href="">
                        <div class="course-box">
                           
                            <div class="logo">
                                <img src="{{assets()}}/images/cources/course1.png">
                            </div>
                            <div class="course-name">Academic Skills</div>
                        </div>
                        </a>
                        

                            <a href="">
                        <div class="course-box">
                            
                            <div class="logo">
                                <img src="{{assets()}}/images/cources/course2.png">
                            </div>
                            <div class="course-name">Analytical skills/Mathematics</div>
                        </div>
                        </a>

                        <a href="">
                        <div class="course-box">
                          
                            <div class="logo">
                                <img src="{{assets()}}/images/cources/course3.png">
                            </div>
                            <div class="course-name">Intelligence/Reasoning(Verbal and Non-Verbal)/div>
                        </div>
                    </div>
                        </a>


                             

                    
                        
                        <a href="">
                        <div class="course-box">
                          
                            <div class="logo">
                                <img src="{{assets()}}/images/cources/course1.png">
                            </div>
                            <div class="course-name">ENGLISH</div>
                        </div>
                        </a>
                                
                    </div>
                </div>
             </div>
        </div>
    </section> -->

    <section class="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <img src="{{assets()}}/images/bout.jpg" alt="about">
                </div>
                <div class="col-lg-7 ps-5">
                    <h4 class="head mb-3 text-muted">About us</h4>
                    <h1 class="title">{{$about->title}}</h1>
                    <div class=" about-short-description">

                        {!!$about->about_description!!}

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="instructors">
        <div class="container">
            <div class="col-lg-10 mx-auto text-center">
                <h1 class="head"><span>Our Skilled Instructors</span></h1>
                <p class="text-muted px-lg-5">Our team of skilled teachers and mentors comprises dedicated experts who bring a wealth of knowledge and experience to the table. They are results-oriented professionals, dedicated to helping students achieve their academic and career aspirations.</p>
                <div class="text-center my-5">
                 <ul class="nav nav-pills justify-content-center gap-3">
                    <li><a data-bs-toggle="pill" href="#teachers" class="active">Teachers</a></li>
                    <li><a data-bs-toggle="pill" href="#mentors"  class="">      Mentors</a></li>
                </ul>
            </div> 
            </div>
            <div class="tab-content">
                <div id="teachers" class="tab-pane fade in active show">
                    <div class="row">
                        @if(count($teachers))

                        @foreach($teachers as $teacher)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="instructor-box">
                                <div class="img">
                                    <img src="{{assets()}}/image/instructor/{{$teacher->instructor_image}}">
                                    <div class="connect">
                                        <ul class="list-unstyled d-flex gap-3">
                                            <li><a href="mailto:"><i class="fa fa-envelope"></i></a></li>
                                            <li><a href="tel:"><i class="fa fa-phone"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="about-teacher">
                                    <h5 class="name">{{$teacher->instructor_name}}</h5>
                                    <p class="course">{{$teacher->skills}}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                            <h3>Coming Soon</h3>
                        @endif
                    </div>
                </div>
                <div id="mentors" class="tab-pane fade in">
                    <div class="row">
                         @if(count($mentors))

                        @foreach($mentors as $mentor)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="instructor-box">
                                <div class="img">
                                    <img src="{{assets()}}/image/instructor/{{$mentor->instructor_image}}">
                                    <div class="connect">
                                        <ul class="list-unstyled d-flex gap-3">
                                            <li><a href="mailto:"><i class="fa fa-envelope"></i></a></li>
                                            <li><a href="tel:"><i class="fa fa-phone"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="about-teacher">
                                    <h5 class="name">{{$mentor->instructor_name}}</h5>
                                    <p class="course">{{$mentor->skills}}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                            <h3>Coming Soon</h3>
                        @endif

                    </div>
                </div>
            </div>
            <!-- <div class="text-center mt-5">
                <a  href="contact.html" class="btn-theme-1">View More</a>
            </div>  -->
        </div>
    </section>

    
    @if(count($whychooses))
    <section class="why-us my-lg-5 my-md-3 my-3 py-lg-5 py-md-3 py-3 ">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="text-center mb-md-5 mb-0 ">
                    <h1 class="head"><span>Why Choose Us</span></h1>
                </div>

                <div class="col-lg-12">
                    <div class="row why-us-list justify-content-center">
                          @foreach($whychooses as $whychoose)
                        <div class="col-lg-6">
                            <div class="why-us-box">
                                <div class="row align-items-center text-start">
                                    <div class="img">
                                        <img src="{{assets()}}/image/whychoose/{{$whychoose->whychoose_image}}" alt="logo">
                                    </div>
                                    <h6>{{$whychoose->whychoose_description}}</h6>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    
    @if(count($ourvalues))
    <section class="our-thoughts" style="background-image:url('{{assets()}}/images/background-image-2.jpg');">
        <div class="container">
            <div class="row">
                @foreach ($ourvalues as $ourvalue)
                <div class="col-lg-4">
                    <div class="our-thoughts-box text-center">
                        <div class="align-items-center">
                            <div class="img mx-auto">
                                <img src="{{assets()}}/images/goal.png" alt="logo">
                            </div>
                            <h3>{{$ourvalue->title}}</h3>
                        </div>
                        <div class="text-start">
                            {!!$ourvalue->description!!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection

@push('scripts')

@endpush