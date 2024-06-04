@extends('layouts.app')
@section('main-content')

<section class="book-your-session">
        <div class="container">
            <div class="row">
            <div>@include('layouts.partials.messages')</div>
                <div class="col-lg-6">
                    <form id="forms" action="{{route('freesession')}}" method="post">
                        @csrf
                        <div class="title">Book Your Free Session</div>
                        
                        <div class="row">

                            <div class="form-group col-lg-6 mb-4 mb-2">
                                <!-- <label class="form-label" for="first_name"> First Name* </label> -->
                        <input class="form-control" type="text" name="first_name" id="first_name" value="" placeholder="First Name*">
                        </div>

                        <div class="form-group mb-4 mb-2 col-lg-6">
                            <!-- <label class="form-label" for="last_name"> Last Name* </label> -->
                            <input class="form-control" type="text" name="last_name" id="last_name" value="" placeholder="Last Name*">
                        </div>

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="mobile"> mobile* </label> -->
                            <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile Number*">
                        </div>

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="email"> Email* </label> -->
                            <input class="form-control" type="email" name="email" id="email" value="" placeholder="E Mail*">
                        </div>

                        <div class="form-group mb-4">
                        <!-- <label class="form-label" for="state"> State </label> -->
                            <select class="form-control form-select" name="state_id">
                                <option disabled selected>Select State</option>
                                @foreach ($states as $state)
                                <option value="{{$state->id}}">{{$state->state_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="btn-theme-1">Schedule</button>
                        </div>

                      </div>
                    </form>
                </div>

                <div class="col-lg-6">
                    <form id="forms-2" action="{{route('enquiry')}}" method="post">
                        @csrf
                        <div class="title">Enquiry Form</div>
                        
                        <div class="row">

                            <div class="form-group col-lg-6 mb-4 mb-2">
                                <!-- <label class="form-label" for="first_name"> First Name* </label> -->
                        <input class="form-control" type="text" name="first_name" id="first_name" value="" placeholder="First Name*">
                        </div>

                        <div class="form-group mb-4 mb-2 col-lg-6">
                            <!-- <label class="form-label" for="last_name"> Last Name* </label> -->
                            <input class="form-control" type="text" name="last_name" id="last_name" value="" placeholder="Last Name*">
                        </div>

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="mobile"> mobile* </label> -->
                            <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile Number*">
                        </div>

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="email"> Email* </label> -->
                            <input class="form-control" type="email" name="email" id="email" value="" placeholder="E Mail*">
                        </div>

                        <div class="form-group mb-4">
                        <!-- <label class="form-label" for="state"> State </label> -->
                            <select class="form-control form-select" name="state_id">
                                <option disabled selected>Select State</option>
                                @foreach ($states as $state)
                                <option value="{{$state->id}}">{{$state->state_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <!-- <label class="form-label" for="message"> Project Details* </label> -->
                            <textarea class="form-control" type="tel" name="message" id="message" rows="4" value="" placeholder="Enter your Requirement"></textarea>
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
              <form id="forms-3" action="{{route('feedback')}}" method="post">
                @csrf
                        
                    <div class="row">

                        <div class="form-group col-lg-6 mb-4 mb-2">
                            <!-- <label class="form-label" for="first_name"> First Name* </label> -->
                            <input class="form-control" type="text" name="first_name" id="first_name" value="" placeholder="First Name*">
                        </div>

                        <div class="form-group mb-4 mb-2 col-lg-6">
                            <!-- <label class="form-label" for="last_name"> Last Name* </label> -->
                            <input class="form-control" type="text" name="last_name" id="last_name" value="" placeholder="Last Name*">
                        </div>

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="mobile"> mobile* </label> -->
                            <input class="form-control" type="text" name="mobile" id="mobile" value="" placeholder="Mobile Number*">
                        </div>

                        <div class="form-group mb-4 mb-2">
                            <!-- <label class="form-label" for="email"> Email* </label> -->
                            <input class="form-control" type="email" name="email" id="email" value="" placeholder="E Mail*">
                        </div>

                        <div class="form-group mb-4">
                            <!-- <label class="form-label" for="message"> Project Details* </label> -->
                            <textarea class="form-control" type="tel" name="message" id="message" rows="4" value="" placeholder="Enter your Feedback"></textarea>
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
<script src="{{assets('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
    $('#forms').validate({
        rules: {
            first_name: "required",
            last_name: "required",
            state_id: "required",
            email: 
            {             
                required: true,
                email: true
            },
            mobile: 
            {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true // Ensures only digits (0-9) are allowed
            },

        },

         messages: {
            first_name: "Enter First Name",
            last_name: "Enter First Name",
            email: "Enter Valid Email ID",
            mobile: "Enter Valid Mobile No",
            state_id: "Select State",
        },
     submitHandler: function(form) {
         form.submit();
     }
    })

    $('#forms-2').validate({
        rules: {
            first_name: "required",
            last_name: "required",
            message: "required",  
            state_id: "required",
            email: 
            {             
                required: true,
                email: true
            },
            mobile: 
            {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true // Ensures only digits (0-9) are allowed
            },

        },

         messages: {
            first_name: "Enter First Name",
            last_name: "Enter First Name",
            email: "Enter Valid Email ID",
            mobile: "Enter Valid Mobile No",
            message: "Enter your requirment",
            state_id: "Select State",
        },
     submitHandler: function(form) {
         form.submit();
     }
    })

    $('#forms-3').validate({
        rules: {
            first_name: "required",
            last_name: "required",
            message: "required",  
            email: 
            {             
                required: true,
                email: true
            },
            mobile: 
            {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true // Ensures only digits (0-9) are allowed
            },

        },

         messages: {
            first_name: "Enter First Name",
            last_name: "Enter First Name",
            email: "Enter Valid Email ID",
            mobile: "Enter Valid Mobile No",
            message: "Enter your requirment",
            state_id: "Select State",
        },
     submitHandler: function(form) {
         form.submit();
     }
    })
</script>
    
@endpush