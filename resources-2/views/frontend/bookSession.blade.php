@extends('layouts.app')
@section('main-content')

<section class="book-your-session">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <form id="contact-form" action="contact.html" method="post">
                        <div class="title">Book Your Free Session</div>

                        <div class="row">

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

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="mobile"> mobile* </label> -->
                            <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile Number*">
                            <div class="validation-error d-none font-size-13">
                        <p class="mb-0">This Field is required</p>
                        </div>
                        </div>

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="email"> Email* </label> -->
                            <input class="form-control" type="email" name="email" id="email" value="" placeholder="E Mail*">
                            <div class="validation-error d-none font-size-13">
                        <p class="mb-0">This Field is required</p>
                        </div>
                        </div>

                        <div class="form-group mb-4">
                        <!-- <label class="form-label" for="state"> State </label> -->
                            <select class="form-control form-select">
                                <option disabled selected>Select State</option>
                                <option>Tamil Nadu</option>
                                <option> Karnataka</option>
                                <option>Kerala</option>
                            </select>
                            <div class="validation-error d-none font-size-13">
                            <p class="mb-0">This Field is required</p>
                        </div>
                    </div>

                        <div>
                            <button type="submit" class="btn-theme-1">Schedule</button>
                        </div>

                      </div>
                    </form>
                </div>

                <div class="col-lg-6">
                    <form id="contact-form" action="contact.html" method="post">
                        <div class="title">Enquiry Form</div>

                        <div class="row">

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

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="mobile"> mobile* </label> -->
                            <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile Number*">
                            <div class="validation-error d-none font-size-13">
                        <p class="mb-0">This Field is required</p>
                        </div>
                        </div>

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="email"> Email* </label> -->
                            <input class="form-control" type="email" name="email" id="email" value="" placeholder="E Mail*">
                            <div class="validation-error d-none font-size-13">
                        <p class="mb-0">This Field is required</p>
                        </div>
                        </div>

                        <div class="form-group mb-4">
                        <!-- <label class="form-label" for="state"> State </label> -->
                            <select class="form-control form-select">
                                <option disabled selected>Select State</option>
                                <option>Tamil Nadu</option>
                                <option> Karnataka</option>
                                <option>Kerala</option>
                            </select>
                            <div class="validation-error d-none font-size-13">
                            <p class="mb-0">This Field is required</p>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                            <!-- <label class="form-label" for="message"> Project Details* </label> -->
                            <textarea class="form-control" type="tel" name="message" id="message" rows="4" value="" placeholder="Enter your Requirement"></textarea>
                            <div class="validation-error d-none font-size-13">
                        <p class="mb-0">This Field is required</p>
                        </div>
                        </div>

                        <div>
                            <button type="submit" class="btn-theme-1">Send Message</button>
                        </div>
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="feedback" data-bs-toggle="modal" data-bs-target="#feedback">
        Feedback
    </div>

    <div class="modal" id="feedback">
        <div class="modal-dialog">
          <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header border-0">
              <h4 class="modal-title">Feedbacks Form</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
              <form id="contact-form" action="contact.html" method="post">

                        <div class="row">

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

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="mobile"> mobile* </label> -->
                            <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile Number*">
                            <div class="validation-error d-none font-size-13">
                        <p class="mb-0">This Field is required</p>
                        </div>
                        </div>

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="email"> Email* </label> -->
                            <input class="form-control" type="email" name="email" id="email" value="" placeholder="E Mail*">
                            <div class="validation-error d-none font-size-13">
                        <p class="mb-0">This Field is required</p>
                        </div>
                        </div>

                    <div class="form-group mb-4">
                            <!-- <label class="form-label" for="message"> Project Details* </label> -->
                            <textarea class="form-control" type="tel" name="message" id="message" rows="4" value="" placeholder="Enter your Feedback"></textarea>
                            <div class="validation-error d-none font-size-13">
                        <p class="mb-0">This Field is required</p>
                        </div>
                        </div>

                        <div>
                            <button type="submit" class="btn-theme-1">Submit Feedback</button>
                        </div>
                      </div>
                    </form>
            </div>

          </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush