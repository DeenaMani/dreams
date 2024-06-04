@extends('layouts.app')

@section('main-content')

{{pr($courses)}}
<section class="course-details">
        <section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image.jpg');" >
            <div class="container">
                <div class="row content">
                    <div class="col-lg-8">
                        <h2 class="title">{{$courses->course_name}}</h2>
                        <p class="content">Lorem ipsum dolor sit amet, consectetur adipiing elit. Int
                            <div class="details">
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
                            </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="description">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">


                        <div class="overview border-bottom">
                            <div class="title h3 mb-3">About The Course</div>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                            </p>
                            <p>
                            It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                            <div class="title h5 mt-3 pb-1">Requirment</div>
                            <ol>
                                <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry</li>
                                <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry</li>
                            </ol>
                            <div class="title h5 mt-3 pb-1">What You Will Learn?</div>
                            <ul class="row">
                                <li class="col-md-6">Lorem Ipsum is simply dummy text of the printing</li>
                                <li class="col-md-6">Lorem Ipsum is simply dummy text of the printing</li>
                                <li class="col-md-6">Lorem Ipsum is simply dummy text of the printing</li>
                                <li class="col-md-6">Lorem Ipsum is simply dummy text of the printing</li>
                                <li class="col-md-6">Lorem Ipsum is simply dummy text of the printing</li>
                                <li class="col-md-6">Lorem Ipsum is simply dummy text of the printing</li>
                            </ul>
                        </div>
                        <div class="curriculum border-bottom">
                            <div class="title h3 mb-3">Curriculum</div>
                            <div class="curriculum-list">
                                <div class="curriculum-item">
                                    <div class="head" data-bs-toggle="collapse" data-bs-target="#demo">
                                        Natural Numbers<span class="duration">3:30:00 hours</span>
                                    </div>
                                    <div id="demo" class="collapse body">
                                        <ul class="list-unstyled">
                                            <li class="">
                                                <div class="class"><span class="pe-3 lesson"><i class="fa fa-circle-play pe-2"></i></span>
                                                <span class="topic">Natural Numbers<i class="fa fa-angle-down"></i></span></div>
                                                <span class="duration">3:30:00 hours</span>
                                            </li>
                                            <li class="">
                                                <div class="class"><span class="pe-3 lesson"><i class="fa fa-circle-play pe-2"></i></span>
                                                <span class="topic">Fundamental Of Analytical<i class="fa fa-angle-down"></i></span></div>
                                                <span class="duration">1:20:00 hours</span>
                                            </li>
                                            <li class="">
                                                <div class="class"><span class="pe-3 lesson"><i class="fa fa-circle-play pe-2"></i></span>
                                                <span class="topic">Unitary Method<i class="fa fa-angle-down"></i></span></div>
                                                <span class="duration">1:20:00 hours</span>
                                            </li>
                                            <li class="">
                                                <div class="class"><span class="pe-3 lesson"><i class="fa fa-circle-play pe-2"></i></span>
                                                <span class="topic">Fractions<i class="fa fa-angle-down"></i></span></div>
                                                <span class="duration">1:20:00 hours</span>
                                            </li>
                                            <li class="">
                                                <div class="class"><span class="pe-3 lesson"><i class="fa fa-circle-play pe-2"></i></span>
                                                <span class="topic">Ratio and Proportion<i class="fa fa-angle-down"></i></span></div>
                                                <span class="duration">1:20:00 hours</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="curriculum-item">
                                    <div class="head" data-bs-toggle="collapse" data-bs-target="#demo">
                                        Natural Numbers<span class="duration">3:30:00 hours</span>
                                    </div>
                                    <div id="demo" class="collapse body">
                                        <ul class="list-unstyled">
                                            <li class="">
                                                <div class="class"><span class="pe-3 lesson"><i class="fa fa-circle-play pe-2"></i></span>
                                                <span class="topic">Natural Numbers<i class="fa fa-angle-down"></i></span></div>
                                                <span class="duration">3:30:00 hours</span>
                                            </li>
                                            <li class="">
                                                <div class="class"><span class="pe-3 lesson"><i class="fa fa-circle-play pe-2"></i></span>
                                                <span class="topic">Fundamental Of Analytical<i class="fa fa-angle-down"></i></span></div>
                                                <span class="duration">1:20:00 hours</span>
                                            </li>
                                            <li class="">
                                                <div class="class"><span class="pe-3 lesson"><i class="fa fa-circle-play pe-2"></i></span>
                                                <span class="topic">Unitary Method<i class="fa fa-angle-down"></i></span></div>
                                                <span class="duration">1:20:00 hours</span>
                                            </li>
                                            <li class="">
                                                <div class="class"><span class="pe-3 lesson"><i class="fa fa-circle-play pe-2"></i></span>
                                                <span class="topic">Fractions<i class="fa fa-angle-down"></i></span></div>
                                                <span class="duration">1:20:00 hours</span>
                                            </li>
                                            <li class="">
                                                <div class="class"><span class="pe-3 lesson"><i class="fa fa-circle-play pe-2"></i></span>
                                                <span class="topic">Ratio and Proportion<i class="fa fa-angle-down"></i></span></div>
                                                <span class="duration">1:20:00 hours</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="instructor">
                            <div class="title h3 mb-3">Your Instructor</div>
                            <div class="instructor-list">
                                <div class="instructor-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3">
                                            <div class="image">
                                                <img src="{{assets()}}/images/instructors/teacher1.jpg">
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-md-9">
                                            <div class="instructor-name"> DEVID MARK </div>
                                            <div class="instructor-specification">
                                                <span class="pe-4"><i class="fa fa-play pe-2"></i> 12 Courses</span>
                                                <span><i class="fa fa-user pe-2"></i> 2,234 Students</span>
                                            </div>
                                            <div class="skills">Skills: Analytical, Trigonometry</div>
                                            <div class="about-instructor">
                                                Several carried through an of up attempt gravity. Situation to be at offending elsewhere distrusts if. Particular use for considered projection cultivated. Worth of do doubt shall
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="instructor-box">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3">
                                            <div class="image">
                                                <img src="{{assets()}}/images/instructors/teacher2.jpg">
                                            </div>
                                        </div>
                                        <div class="col-lg-9 col-md-9">
                                            <div class="instructor-name"> DEVID MARK </div>
                                            <div class="instructor-specification">
                                                <span class="pe-4"><i class="fa fa-play pe-2"></i> 12 Courses</span>
                                                <span><i class="fa fa-user pe-2"></i> 2,234 Students</span>
                                            </div>
                                            <div class="skills">Skills: Analytical, Trigonometry</div>
                                            <div class="about-instructor">
                                                Several carried through an of up attempt gravity. Situation to be at offending elsewhere distrusts if. Particular use for considered projection cultivated. Worth of do doubt shall
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                                        ₹ 1300 <del>₹ 1500</del>
                                        <span class="offer">13% OFF</span>
                                    </div>
                                    <div class="add-to-cart">
                                        <a class="btn-theme-1" href=""> <i class="fa fa-cart-shopping pe-3"></i> Add To Cart</a>
                                    </div>

                                    <div class="course-spec-info">
                                        <div class="spec-list">
                                            <div class="spec-1">
                                                <i class="fa fa-clock pe-3"></i>Duration
                                            </div>
                                            <div class="spec-2">
                                                40 Hours
                                            </div>
                                        </div>
                                        <div class="spec-list">
                                            <div class="spec-1">
                                                <i class="fa fa-play pe-3"></i>Lecture
                                            </div>
                                            <div class="spec-2">
                                                4 lectures
                                            </div>
                                        </div>
                                        <div class="spec-list">
                                            <div class="spec-1">
                                                <i class="fa fa-filter pe-3"></i>Level
                                            </div>
                                            <div class="spec-2">
                                                Advanced
                                            </div>
                                        </div>
                                        <div class="spec-list">
                                            <div class="spec-1">
                                                <i class="fa fa-language pe-3"></i>Language
                                            </div>
                                            <div class="spec-2">
                                                English
                                            </div>
                                        </div>
                                        <div class="spec-list">
                                            <div class="spec-1">
                                                <i class="fa fa-database pe-3"></i>Assessments
                                            </div>
                                            <div class="spec-2">
                                                Yes
                                            </div>
                                        </div>

                                        <div class="about-material">
                                            <div class="title">Study Materials</div>
                                            <div class="study-materials pt-4">
                                                <i class="fa fa-play pe-2"></i> Video
                                            </div>
                                            <div class="study-materials">
                                                <i class="fa fa-book pe-2"></i> book
                                            </div>
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

@endpush