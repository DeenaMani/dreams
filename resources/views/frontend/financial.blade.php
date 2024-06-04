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
	<section class="financial">
		<div class="container">
			<div class="title">Total Pooled Money</div>

			<div class="col-lg-7 mx-auto">
				<table class="table table-bordered table-hover">
					<thead>
					<tr>
						<th>Year</th>
						<th>Amount(Rupees)</th>
					</tr>
					</thead>
					<tbody>
					@foreach($financials as $financial)
					<tr>
						<td>{{$financial->year}}</td>
						<td>{{$setting->currency}} {{$financial->total_amount}}</td>
					</tr>
					@endforeach
					</tbody>
			  	</table>
			  
			    <div class="event-pagination">
			   		{{ $financials->links('frontend.pagination') }}
				</div>
			</div>
		</div>
	</section>

@endsection