@extends('layouts.app')

@section('content')

	<section class="page-head-section" style="background-image: url('{{url('/')}}/public/image/setting/{{$setting->background_image}}');"> 
		<div class="container">
			<div class="page-head-inner">
				<div class="page-title h1">
					{{$title}}
				</div>
				<nav aria-label="breadcrumb">
				  <ol class="breadcrumb">
				    <li class="breadcrumb-item"><a href="{{url('/')}}">{{$setting->company_name}}</a></li>
				    <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
				  </ol>
				</nav>
			</div>
		</div>
	</section>

	<section class="donate">
		<div class="container">

			<div class="note">
				<p>NOTE:</p>
					Please put your  <a href="javascript:void(0)" class="btn-link"> registered mobile </a> in the comments while transferring money Please share payment details screenshot from your mail id to <a href="mailto:{{$setting->email}}" class="btn-link"> {{$setting->email}}	</a>			
			</div>

			<div class="bank-transfer">
	     	<button class="title-btn">Bank Transfer Details</button>
                <div class="bank-details">
					<table class="table table-bordered table-hover">
				    <tbody>
				      <tr>
				        <td>NAME</td>
				        <td>{{$donate->name}}</td>
				      </tr>
				      <tr>
				        <td>ACCOUNT NUMBER</td>
				        <td>{{$donate->account_number}}</td>
				      </tr>
				      <tr>
				        <td>BANK NAME</td>
				        <td>{{$donate->bank}}</td>
				      </tr>
				      <tr>
				        <td>IFSC CODE</td>
				        <td>{{$donate->ifsc_code}}</td>
				      </tr>
				      <tr>
				        <td>MOBILE NUMBER</td>
				        <td>{{$donate->phone}}</td>
				      </tr>
				      <tr>
				        <td>MAIL ID</td>
				        <td>{{$donate->email}}</td>
				      </tr>
				    </tbody>
				  </table>
				</div>
			</div>

			<div class="bank-transfer">
	     	<button class="title-btn">UPI Payment Details</button>
        <div class="bank-details">
					<div class="row align-items-center">
						<div class="col-lg-4 col-md-6 col-sm-6">
							<div class="upi-option">
								<img src="{{url('/')}}/public/assets/images/gpay.png" alt="google-pay">
								<img src="{{url('/')}}/public/assets/images/phonepay.png" alt="phone-pay">
								<img src="{{url('/')}}/public/assets/images/paytm.png" alt="paytm">
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							<div class="form-group upi-detail">
								<input class="form-control" value="{{$donate->upi_number}}" readonly disabled>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection