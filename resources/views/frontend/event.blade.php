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
				    <li class="breadcrumb-item"><a href="{{url('/')}}"> {{$setting->company_name}}</a></li>
				    <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
				  </ol>
				</nav>
			</div>
		</div>
	</section>

	<section class="events">
		<div class="container">
			<div class="event-list">
				<div class="row">
					@foreach ($events as $event)
					<div class="col-lg-6 mb-4">
						<div class="event">
							<div class="type">@if($event->type == 1) Online @else($event->type == 1) Offline @endif</div>
							<div class="event-title">{{$event->title}}</div>
							<div class="date"><i class="fa-regular fa-calendar pe-2"></i>{{$event->date}}</div>
							<div class="event-details">{!!$event->description!!}</div>
							<div class="meeting">
								<div class="mb-1">@if($event->type == 1) Zoom Link : @else($event->type == 1)  Goole Map : @endif</div>
								<a href="{{$event->meeting_link}}" target="_blank" class="btn-link">
							 		{{$event->meeting_link}} 	
							 	</a>
							</div>
						</div>
					</div>
					@endforeach
				</div>

				<div class="event-pagination">
			   		{{ $events->links('frontend.pagination') }}
				</div>
			</div>
		</div>
	</section>

@endsection

</body>
</html>