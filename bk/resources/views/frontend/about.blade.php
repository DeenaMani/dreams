@extends('layouts.app')
@section('main-content')

<section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image-2.jpg');">
        <div class="container">
            <div class="row content">
                <h1>{{$title}}</h1>
            </div>
        </div>
    </section>
    <section class="about about-page bg-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <img src="{{assets()}}/image/about/{{$about->image}}" alt="about">
                </div>
                <div class="col-lg-7 ps-5">
                    <!-- <h6 class="head mb-2 text-muted">About us</h6> -->
                    <h1 class="title">{{$about->title}}</h1>
                    <div class=" about-short-description">
                        {!!html_entity_decode($about->about_description)!!}

                    </div>

                    <!-- <div class="mt-5">
                        <a  href="about.html" class="btn-theme-3"> Apply Now <i class="fa fa-arrow-right ps-3"></i> </a>
                    </div> -->
                </div>
            </div>
            <div class="story">
                <div class="row ">
                    <div class="col-lg-3 col-sm-6 d-flex justify-content-center">
                        <img src="{{assets()}}/images/course.png">
                        <div class=" d-inline-block">
                            <div class="count h2">60</div>
                            <div class="text">Active Courses</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 d-flex justify-content-center">
                        <img src="{{assets()}}/images/teacher.png">
                        <div class=" d-inline-block">
                            <div class="count h2">50</div>
                            <div class="text">Teachers & Mentors</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 d-flex justify-content-center">
                        <img src="{{assets()}}/images/student.png">
                        <div class=" d-inline-block">
                            <div class="count h2">420</div>
                            <div class="text">Students Learning</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 d-flex justify-content-center">
                        <img src="{{assets()}}/images/year.png">
                        <div class=" d-inline-block">
                            <div class="count h2">10</div>
                            <div class="text">Year Experience</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <section class="about-bottom">
        <div class="container">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="head pb-5"><span>What Make Us Spcecial?</span></h2>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="mb-3">Who We Are</h5>
                    <div class="content">
                        {!!html_entity_decode($about->who_we_are)!!}
                    </div>
                </div>
                <div class="col-lg-6">
                    <h5 class="mb-3">What We Do</h5>
                    <div class="content">
                        {!!html_entity_decode($about->what_we_do)!!}
                    </div>
                </div>
            </div>
        </div>
    </section>


    @if(count($faqs))
    <section class="faqs pt--60 pb--60">
        <div class="container-xl">
    
                <h1 class="text-center mb-5">Recently Asked FAQ's</h1>  

                <div class="row  justify-content-center">
                    <div class="col-lg-9 faq-data">
                        @foreach($faqs as $faq)
                        <div class="faqs-section">
                            <div class="card" role="tablist">
                                <a href="javascript:void(0)" class=" card-header title" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#faq{{$faq->id}}">
                                  <h6> {{$faq->faq_title}} </h6>
                                </a>
                                <div id="faq{{$faq->id}}" class="collapse card-body"  data-bs-parent="#accordion">
                                    {!!html_entity_decode($faq->faq_description)!!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div> 
                </div>
        </div>
    </section>
    @endif

@endsection
@push('scripts')
@endpush