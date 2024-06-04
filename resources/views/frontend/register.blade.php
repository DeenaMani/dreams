@extends('layouts.app')

@section('content')

	<section class="login">
		<div class="container">
			<div class="col-lg-8 login-form m-auto">
			@include('layouts.partials.messages')
					<form class="row" action="{{url('/client/store')}}" method="post" onsubmit="return validateForm()">

					@csrf
					@method('POST')
						<div class="title">JOIN THE PAINT ARMY</div>

						<div class="row">

							<div class="form-floating col-lg-6 mb-4 mb-2">
						    <input class="form-control" type="text" name="first_name" id="first_name" value="" placeholder="E Mail">
						    <div class="validation-error d-none font-size-13">
			            <p class="mb-0">First Name is required</p>
				        </div>
						    <label for="first_name"> First Name </label>
					    </div>

					    <div class="form-floating col-lg-6 mb-4 mb-2">
						    <input class="form-control" type="text" name="middle_name" id="middle_name" value="" placeholder="Middle Name">
						    <div class="validation-error d-none font-size-13">
			            <p class="mb-0">Middle Name is required</p>
				        </div>
						    <label for="middle_name"> Middle Name </label>
					    </div>

					    <div class="form-floating col-lg-6 mb-4 mb-2">
						    <input class="form-control" type="text" name="last_name" id="last_name" value="" placeholder="Last Name">
						    <div class="validation-error d-none font-size-13">
			            <p class="mb-0">Last Name is required</p>
				        </div>
						    <label for="last_name"> Last Name </label>
					    </div>

					    <div class="form-floating mb-4 mb-2 col-lg-6">
						    <input class="form-control" type="tel" name="phone" id="phone" value="" placeholder="E Mail">
						    <div class="validation-error d-none font-size-13">
			            <p class="mb-0">Phone No is required</p>
				        </div>
						    <label for="phone"> Phone No </label>
					    </div>

					    <div class="form-floating mb-4 mb-2 col-lg-6 col-lg-6">
						    <input class="form-control" type="email" name="email" id="email" value="" placeholder="E Mail">
						    <div class="validation-error d-none font-size-13">
			            <p class="mb-0">E Mail is required</p>
				        </div>
						    <label for="email"> E mail </label>
					    </div>

					    <div class="form-group mb-4 mb-2 col-lg-6">					    	
						    <select class="form-select form-control" id="country" name="country_id">
					        <option value="0" selected disabled>Select Country</option>
					        @foreach ($countries as $country)
								<option value="{{$country->id}}">{{$country->country_name}}</option>
							@endforeach
					      </select>
						    <div class="validation-error d-none font-size-13">
			            <p class="mb-0">Please select country</p>
				        </div>
					    </div>

					    <div class="form-group mb-4 mb-2 col-lg-6">					    	
						    <select class="form-select form-control" id="state" name="state_id">
					        <option value="0" selected disabled>Select State</option>
					        
					      </select>
						    <div class="validation-error d-none font-size-13">
			            <p class="mb-0">please select State</p>
				        </div>
					    </div>

					    <div class="form-floating mb-4 mb-2 col-lg-6">					    	
						    <input class="form-control" type="text" name="district" id="district" value="" placeholder="District">
						    <div class="validation-error d-none font-size-13">
			            <p class="mb-0">District is required</p>
				        </div>
						    <label for="district"> District </label>
					    </div>
					    <div class="form-floating mb-4 mb-2 col-lg-6">					    	
						    <input class="form-control" type="text" name="place" id="place" value="" placeholder="Place">
						    <div class="validation-error d-none font-size-13">
			            <p class="mb-0">Place is required</p>
				        </div>
						    <label for="place"> Place </label>						    
					    </div>
					    <div class="form-floating mb-4 mb-2 col-lg-6">					    	
						    <input class="form-control" type="text" name="pincode" id="pincode" value="" placeholder="Pin Code / Zip Code">
						    <div class="validation-error d-none font-size-13">
			            <p class="mb-0">Pin Code / Zip Code is required</p>
				        </div>
						    <label for="pincode"> Pin Code / Zip Code </label>					    
					    </div>

						<p class="text-muted"><small> By Register means you are agreed to our tems and conditions</small></p>

					    <div>
					    	<button type="submit" class="btn-style-1">Register</button>
					    </div>

					</form>
				</div>
		</div>
	</section>

@endsection

@push('scripts')

<script>
	window.onscroll = function() {myFunction()};

	var header = document.getElementById("header-sticky");
	var sticky = header.offsetTop;

	function myFunction() {
	  if (window.pageYOffset > sticky) {
	    header.classList.add("sticky");
	  } else {
	    header.classList.remove("sticky");
	  }
	}

	$('#country').change(function() {
		var id = $(this).val();
		var stateSelect = $('#state');
		if (id !== '') {
		
			$.ajax({
				url: "{{URL('/')}}/get-state/" + id ,
				method: 'GET',
				data: { country_id: id },
				success: function(data) {
					// Update the state dropdown options
					var stateSelect = $('#state');
					stateSelect.empty();
					stateSelect.append($('<option>').val('').text('Select State').prop('disabled', true).prop('selected', true));
					$.each(data, function(index, state) {
						stateSelect.append($('<option>').val(state.id).text(state.state_name));
					});
				}
			});
		} else {
			stateSelect.empty();
		}
	});

	function handleInput() {
		var input = this;
		var error = document.querySelector("#" + input.id + " + .validation-error");
		if (input.value.trim() === "") {
			error.classList.remove("d-none");
		} else {
			error.classList.add("d-none");
		}
	}


	function attachInputEventListeners() {
		var inputs = document.querySelectorAll("input[type='text'], input[type='email'], textarea");
		inputs.forEach(function(input) {
			input.addEventListener("input", handleInput);
		});
	}

	function validateForm() {
    var isValid = true;

    // Validate First Name
    var firstNameInput = document.getElementById("first_name");
    var firstNameError = document.querySelector("#first_name + .validation-error");
    if (firstNameInput.value.trim() === "") {
        firstNameError.classList.remove("d-none");
        isValid = false;
    } else {
        firstNameError.classList.add("d-none");
    }

    // Validate Email
	var emailInput = document.getElementById("email");
	var emailError = document.querySelector("#email + .validation-error");
	var emailRegex = /^\S+@\S+\.\S+$/;
	if (emailInput.value.trim() === "") {
		emailError.textContent = "Email is required";
		emailError.classList.remove("d-none");
		isValid = false;
	} else if (!emailRegex.test(emailInput.value.trim())) {
		emailError.textContent = "Invalid email format";
		emailError.classList.remove("d-none");
		isValid = false;
	} else {
		emailError.classList.add("d-none");
	}

	// Validate Phone No
	var phoneInput = document.getElementById("phone");
	var phoneError = document.querySelector("#phone + .validation-error");
	var phoneRegex = /^\+?[0-9]*$/;
	if (phoneInput.value.trim() === "") {
		phoneError.textContent = "Phone number is required";
		phoneError.classList.remove("d-none");
		isValid = false;
	} else if (!phoneRegex.test(phoneInput.value.trim())) {
		phoneError.textContent = "Invalid phone number";
		phoneError.classList.remove("d-none");
		isValid = false;
	} else {
		phoneError.classList.add("d-none");
	}

    // Validate District
    var districtInput = document.getElementById("district");
    var districtError = document.querySelector("#district + .validation-error");
    if (districtInput.value.trim() === "") {
        districtError.classList.remove("d-none");
        isValid = false;
    } else {
        districtError.classList.add("d-none");
    }

    // Validate Place
    var placeInput = document.getElementById("place");
    var placeError = document.querySelector("#place + .validation-error");
    if (placeInput.value.trim() === "") {
        placeError.classList.remove("d-none");
        isValid = false;
    } else {
        placeError.classList.add("d-none");
    }

    var countrySelect = document.getElementById("country");
    var countryError = document.querySelector("#country + .validation-error");
    if (countrySelect.value === "0") { // Assuming "0" represents the default "Select Country" option
        countryError.classList.remove("d-none");
        isValid = false;
    } else {
        countryError.classList.add("d-none");
    }

    // Validate State
    var stateSelect = document.getElementById("state");
    var stateError = document.querySelector("#state + .validation-error");
    if (stateSelect.value === "0") { // Assuming "0" represents the default "Select State" option
        stateError.classList.remove("d-none");
        isValid = false;
    } else {
        stateError.classList.add("d-none");
    }

    // Validate Pincode
    var pincodeInput = document.getElementById("pincode");
    var pincodeError = document.querySelector("#pincode + .validation-error");
    if (pincodeInput.value.trim() === "") {
        pincodeError.classList.remove("d-none");
        isValid = false;
    } else {
        pincodeError.classList.add("d-none");
    }

    if (isValid) {
        var errorMessages = document.querySelectorAll('.validation-error');
        errorMessages.forEach(function(error) {
            error.classList.add('d-none');
        });
    }

    return isValid;
	}

	document.addEventListener("DOMContentLoaded", function() {
		attachInputEventListeners();

		var form = document.querySelector("form");
		form.addEventListener("submit", handleFormSubmit);
	});

</script>

@endpush