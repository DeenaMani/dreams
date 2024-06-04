@extends('layouts.app')
@section('main-content')


@php
	$setting = cache('settings');
	$total = 0 ;
	foreach($cart as $row) {

	    $cart_type = $row->cart_type ;
	    if($cart_type == 1){
	         $category = App\Models\Category::where('id',$row->product_id)->where('status',1)->first();
	        $total += $category->price;
	    }
	}
 @endphp


        
	<section class="checkout">
		<div class="container">
			<div class="h1 text-center mb-md-5 md-3">Checkout</div>
			<div class="row">
				<div class="col-lg-7">
					@if(empty(@$user->email))
						<div class="login-account">
							<h6 class="text-muted mb-0">Please log in to enroll in the course?<a href="{{url('login')}}" class="btn-link"> Click here to login</a></h6 class="text-muted">
						</div>
					@endif

					<div class="billing-details">
						<div class="title">Billing Details</div>
						<form method="post" action="{{url('checkout/post')}}" id="checkoutPost">
							 @csrf
		    				<div class="row">
		    					<div class="col-sm-6 mb-3">
				    				<label>First Name<span class="text-danger">*</span></label>
				    				<input type="text" name="first_name" id="first_name" placeholder="Enter First Name"  value="{{@$user->first_name}}" class="form-control" required>
				    			</div>
				    			<div class="col-sm-6 mb-3">
				    				<label>Last Name<span class="text-danger">*</span></label>
				    				<input type="text" name="last_name" id="last_name" placeholder="Enter Last Name"  value="{{@$user->last_name}}" class="form-control" required>
				    			</div>
				    			<div class="col-lg-6 mb-3">
				    				<label>E Mail<span class="text-danger">*</span></label>
				    				<input type="email" name="email" id="email" placeholder="Enter E-Mail Address" class="form-control" value="{{@$user->email}}" required>
				    			</div>
				    			<div class="col-lg-6 mb-3">
				    				<label>Phone Number<span class="text-danger">*</span></label>
				    				<input type="text" name="phone" id="phone" placeholder="Enter Phone No" class="form-control"  value="{{@$user->phone}}" required>
				    			</div>
				    			<div class="col-sm-12 mb-3">
				    				<label>Address Line <span class="text-danger">*</span></label>
				    				<input type="text" name="address" id="address" placeholder="Enter Address" class="form-control" required>
				    			</div>
				    			<div class="col-sm-4 mb-3">
				    				<label>State<span class="text-danger">*</span></label>
				    				<select class="form-select form-control" name="state" required>
				    					<option value="" >Select State</option>
				    					@if(count($states))
				    					@foreach($states as $state)
				    					<option value="{{$state->id}}"  >{{$state->state_name}}</option>
				    					@endforeach
				    					@endif
				    					
				    				</select>
				    			</div>
				    			<div class="col-sm-4 mb-3">
				    				<label>Town / City<span class="text-danger">*</span></label>
				    				<input type="text" name="city" id="city" placeholder="Enter Town / City" class="form-control"  required>
				    			</div>
				    			
				    			<div class="col-sm-4 mb-3">
				    				<label>Pincode<span class="text-danger">*</span></label>
				    				<input type="text" name="pincode" id="pincode" placeholder="Enter Pincode" class="form-control" required>
				    			</div>
			    			</div>
			    				<p><button type="submit" class="btn-theme-1">Continue to pay</button></p>
	    			</form>

	    			
	    		


			
      
	    		</div>
	    		

	    		<!-- <div class="payment-method" style="display: none;">
	    			
	    			
        
	    		</div>
 		-->
	    		
				</div>
				<div class="col-lg-5">
					<div class="order-summery">
	    			<div class="items">
	    				<h5 class="title">Order Summery</h5>
	    				 @if(count($cart))
            @php $total= 0 @endphp
            @foreach($cart as $row)
				    				 @php
			                $cart_type = $row->cart_type ;
			                if($cart_type == 1){
			                     $category = App\Models\Category::where('id',$row->product_id)->where('status',1)->first();
			              
			                    $total += $category->price
			             @endphp
			               <div class="order-course">
				    			<div class="row">
				    				<!-- <div class="col-lg-3 col-sm-2 col-3 text-center">
				    					<img src="assets/images/cources/course1.jpg" alt="course1">
				    				</div> -->
				    				<div class="col-lg-8 col-md-7 col-sm-7 col-7">
				    					<h6 class="course-name"> {{$category->category_name}}</h6>

				    				</div>
				    				<div class="col-lg-4 col-md-5 col-sm-5 col-5">
				    					<div class="course-price">{{get_price($category->price)}}  </div>
				    			    </div>
				    			</div>
				    		</div>
			              @php }
			              else{


			              	$course= App\Models\Course::where('id',$row->product_id)->where('status',1)->first(); $total += $course->price;
			                @endphp
			                


			                <div class="order-course">
				    			<div class="row">
				    				<!-- <div class="col-lg-3 col-sm-2 col-3 text-center">
				    					<img src="assets/images/cources/course1.jpg" alt="course1">
				    				</div> -->
				    				<div class="col-lg-8 col-md-7 col-sm-7 col-7">
				    					<h6 class="course-name"> {{$course->course_name}}</h6>

				    				</div>
				    				<div class="col-lg-4 col-md-5 col-sm-5 col-5">
				    					<div class="course-price">{{get_price($course->price)}}  </div>
				    			    </div>
				    			</div>
				    		</div>
			                @php } @endphp

			               
            @endforeach
            @endif
			                           
			    		
						</div>
						<div class="course-total">
							<div class="sub-total"><span class="th">Subtotal</span><span class="td">{{get_price($total)}}</span></div>
							<!-- <div class="tax-fee"><span class="th">Tax Fee</span><span class="td">₹ 100</span></div> -->
							<div class="total"><span class="th">Total</span><span class="td">{{get_price($total)}}</span></div>
						</div>
					</div>

					<div class="order-summery mt-5 text-muted">
						Order's cannot be canceled afrer placed order. Please Make sure your are choosing the right course for you.
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection
@push('scripts')
<script src="{{assets('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
	$('#checkoutPost').validate({

    	submitHandler: function(f) {
    		var email = $("#email").val();
    		var phone = $("#mobile").val();

			var form = $("#checkoutPost");
			//alert(form.serialize());
			$.ajax({
			type:'POST',
			url:'{{url("checkoutPost")}}',
			dataType: 'json',
			data: form.serialize(),
			success:function(data) {
				// console.log(data.bookingId);
				// console.log(data.bookingId);
				//$(".payment-method").show();
				// $(".payment-method script").attr("data-prefill.name",email);
				// $(".payment-method script").attr("data-prefill.email",phone);
				// $(".payment-method script").attr("data-prefill.order_id",bookingId);
				// $(".payment-method script").attr("data.orderId",bookingId);
				//$("#bookingId").val(data.bookingId)
				
				window.location = "{{url('payments')}}/"+ data.bookingId;

				//$("#paymentCard").submit();
			}
        });
        return false;
    },
    // other options
});
</script>



@endpush