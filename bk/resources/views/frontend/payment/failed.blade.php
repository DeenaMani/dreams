@extends('layouts.app')
@section('main-content')
		<section class="confirmation">
		<div class="container">
			<div class="text-center">
				 <img src="{{assets()}}images/cancel.png" width="60x" class="mb-1" alt="checked">
		     <div class="h3 my-3 font--700">Your booking has been failed</div>
		     <!-- <p>Your Purchase has been confirmed. Confirmation mail and details will be send to your mail  address Given by you</p> -->
		     <div class="h4 font--700">Purchase id : #{{$booking->booking_no}}</div>
	    </div>
	    <div class="text-center py-4">
	    	<a href="{{url('courses')}}" class="btn-theme-1">Continue Purchase</a>
	    	<div class="my-3">Or Want to <a href="{{url('login')}}" class="btn-link"> View Your Order</a></div>
	    </div>
	    <div class="booking-details">
	    	<div class="row justify-content-center">
							<div class="col-lg-7"> 
								<div class="purchase-details">
									<h4 class="title">Course Details</h4>
									@if(count($bookingProduct))
									@foreach($bookingProduct as $row)
									<div class="course">
						    		
										<div class="course-details">
											<div class="h6 course-name"> {{$row->product_name}}</div>
											<div class="price">{{$booking->currency}} {{$row->product_price}}</div>
										</div>
									</div>
									@endforeach
									@endif
									
								</div>
							</div>
							<div class="col-lg-5 ">
								<div class="purchase-details">
									<div class="price-details">
										<h5 class="title">Purchase Details</h5>
										<div class="sub-total"><span class="th">Subtotal</span><span class="td">{{$booking->currency}} {{$booking->sub_total}}</span></div>
										<!-- <div class="tax-fee"><span class="th">Tax Fee</span><span class="td">₹ {{$booking->booking_no}}</span></div> -->
										<div class="total"><span class="th">Total</span><span class="td">{{$booking->currency}} {{$booking->total_price}}</span></div>
									</div>
								</div>
							</div>
						</div>
					</div>
		    </div>
	    </div>
		</div>
	</section>
@endsection
@push('scripts')


@endpush