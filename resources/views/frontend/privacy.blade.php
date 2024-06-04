@extends('layouts.app')

@section('content')  
    <div class="breadcrumbs pt-4">
		<div class="container">
			<div class="title h1 pb-2">
				{{$terms->title}}
			</div>
		  <!-- <ol class="breadcrumb m-0">
		    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
		    <li class="breadcrumb-item active">Terms and Condition</li>
		  </ol> -->
		</div>
	</div>

	<section class="account-details mb-5 mt-4">
		<div class="container">
			<div class="terms-conditions">
				{!!$terms->full_description!!}
			</div>	
		</div>
	</section>

@endsection