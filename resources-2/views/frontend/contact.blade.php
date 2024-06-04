@extends('layouts.app')
@section('main-content')

<section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image-2.jpg');">
        <div class="container">
            <div class="row content">
                <h1>Contact Us</h1>
            </div>
        </div>
    </section>

    <div class="contact">
        <div class="container">
            <div class="row">
                <div class="contact-info">
                    <div class="text-center py-3 m4-5">
                        <h3 class=""> Contact Info </h3>
                        <p class="text-muted">We are here for you don't hesitate to ask us,
                        Feel Free to contact us in bussiness Hours</p>
                    </div>
                    <div class="row m-0">
                        <div class="col-lg-4 col-md-4 pt-3 mb-3">
                            <div class="location">
                                <div class="icon"><i class="fa fa-clock text-color-1 pe-3"></i><span></span></div>
                                <div class="contact-info-detail">
                                    <div class="title">Working Hours</div> 
                                    <p>Monday - Friday: 09:00 - 20:00</p>
                                    <p>Sunday & Saturday: 10:30 - 22:00</p>
                                </div>  
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 pt-3 mb-3">
                            <div class="location">
                                <div class="icon"><i class="fa fa-phone text-color-1 pe-3"></i></div>
                                <div class="contact-info-detail"> 
                                    <div class="title">contact</div>
                                    <p>Phone: <span><a href="tel:" class="mb-2">{{$setting->email}}</a></span></p>
                                    <p>E-Mail: <span><a href="mailto:" class="mb-2">{{$setting->phone}}</a></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 pt-3 mb-3">
                            <div class="location">
                                <div class="icon"><i class="fa fa-location-dot text-color-1 pe-3"></i></div>
                                <div class="contact-info-detail"> 
                                    <div class="title">Address</div>
                                    <p>{!!$setting->address!!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="contact-form">
        <div class="container">
            <div class="col-md-8 mx-auto">
                <form class=" row" id="contact-form" action="contact.html" method="post">
                    <div class="title">Fill the form below so we can get to know you</div>
                    <div class="form-group col-lg-6 mb-4 mb-2">
                        <!-- <label class="form-label" for="first-name"> First Name* </label> -->
                        <input class="form-control" type="text" name="first-name" id="first-name" value="" placeholder="First Name*">
                        <div class="validation-error d-none font-size-13">
                            <p class="mb-0">This Field is required</p>
                        </div>
                    </div>

                    <div class="form-group mb-4 mb-2 col-lg-6">
                        <!-- <label class="form-label" for="last-name"> Last Name* </label> -->
                        <input class="form-control" type="text" name="last-name" id="last-name" value="" placeholder="Last Name*">
                        <div class="validation-error d-none font-size-13">
                            <p class="mb-0">Email must be a valid email address</p>
                        </div>
                    </div>

                    <div class="form-group mb-4 mb-2 col-lg-6">
                        <!-- <label class="form-label" for="email"> Email* </label> -->
                        <input class="form-control" type="email" name="email" id="email" value="" placeholder="E Mail*">
                        <div class="validation-error d-none font-size-13">
                            <p class="mb-0">This Field is required</p>
                        </div>
                    </div>

                    <div class="form-group mb-4 col-lg-6">
                        <!-- <label class="form-label" for="phone"> Phone number (If optional) </label> -->
                        <input class="form-control" type="tel" name="phone" id="phone" value="" placeholder="Phone No">
                        <div class="validation-error d-none font-size-13">
                            <p class="mb-0">This Field is required</p>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <!-- <label class="form-label" for="message"> Project Details* </label> -->
                        <textarea class="form-control" type="tel" name="message" id="message" rows="4" value="" placeholder="Enter your Requirement*"></textarea>
                        <div class="validation-error d-none font-size-13">
                            <p class="mb-0">This Field is required</p>
                        </div>
                    </div>
                    <div><button type="submit" class="btn-theme-1">Send Message</button></div>
                </form>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
@endpush