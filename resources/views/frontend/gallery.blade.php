@extends('layouts.app')

@push('links')
<link rel="stylesheet" href="{{url('/')}}/public/assets/css/magnific-popup.css">
@endpush
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

	<section class="gallery">
		<div class="container">
			<div class="year">
				<ul class="list list-unstyled" id="yearContainer">
					@foreach ($uniqueYears as $uniqueYear)
					<li class="{{ request()->is('gallery/' . $uniqueYear . '*') ? 'active' : '' }}">
						<a href="{{url('/gallery/'.$uniqueYear)}}">{{$uniqueYear}}</a>
					</li>
					@endforeach
				</ul>
				<div class="btn-scroll">
					<button class="scroll-btn" id="scrollLeftBtn">&lt;</button>
					<button class="scroll-btn" id="scrollRightBtn">&gt;</button>
				</div>
			</div>
			<div class="gallery-photos">
				<div class="title">PHOTOS</div>
				<div class="row photo">
					@foreach($galleries as $gallery)
					@if($gallery->type == '1')
					<div class="col-lg-4 col-md-6 col-sm-6">
						<a class="image-popup-vertical-fit" href="{{url('/')}}/public/image/gallery/{{$gallery->image}}" title="{{$gallery->title}}">
						<div class="gallery-image">
							<img src="{{url('/')}}/public/image/gallery/{{$gallery->image}}" alt="{{$gallery->title}}">
							<div class="gallery-overlay">
								<div class="name">
									{{$gallery->title}}
								</div>
							</div>
						</div>
						</a>
					</div>
					@endif
					@endforeach

					@foreach($photos as $gallery)
					<div class="col-lg-4 col-md-6 col-sm-6">
						<a class="image-popup-vertical-fit" href="{{url('/')}}/public/image/gallery/{{$gallery->image}}" title="{{$gallery->title}}">
						<div class="gallery-image">
							<img src="{{url('/')}}/public/image/gallery/{{$gallery->image}}" alt="{{$gallery->title}}">
							<div class="gallery-overlay">
								<div class="name">
									{{$gallery->title}}
								</div>
							</div>
						</div>
						</a>
					</div>
					@endforeach

				</div>
			</div>

			<div class="gallery-videos">
				<div class="title">VIDEOS</div>
				<div class="row video">
				@foreach($galleries as $gallery)
					@if($gallery->type == '2')
					<div class="col-lg-4 col-md-6 col-sm-6">
						<a class="image-popup-vertical-fit video-play" href="{{$gallery->video_link}}" title="{{$gallery->title}}">
						<div class="gallery-image">
							<img src="{{url('/')}}/public/image/gallery/{{$gallery->image}}" alt="{{$gallery->title}}">
							<div class="gallery-overlay">
								<div class="play">
									<img src="{{url('/')}}/public/assets/images/play.png" alt="play">
								</div>
							</div>
						</div>
						</a>
					</div>
					@endif
					@endforeach

					@foreach($videos as $gallery)
					<div class="col-lg-4 col-md-6 col-sm-6">
						<a class="image-popup-vertical-fit video-play" href="{{$gallery->video_link}}" title="{{$gallery->title}}">
						<div class="gallery-image">
							<img src="{{url('/')}}/public/image/gallery/{{$gallery->image}}" alt="{{$gallery->title}}">
							<div class="gallery-overlay">
								<div class="play">
									<img src="{{url('/')}}/public/assets/images/play.png" alt="play">
								</div>
							</div>
						</div>
						</a>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</section>

@endsection

@push('scripts')

<script src="{{url('/')}}/public/assets/js/magnific-popup.js"></script>
<script src="{{url('/')}}/public/assets/js/magnific-popup.min.js"></script>

<script>

const scrollLeftBtn = document.getElementById('scrollLeftBtn');
const scrollRightBtn = document.getElementById('scrollRightBtn');
const yearContainer = document.getElementById('yearContainer');

let scrollInterval;

function scrollLeft() {
    yearContainer.scrollBy({
        left: -10,
        behavior: 'smooth'
    });
}

function scrollRight() {
    yearContainer.scrollBy({
        left: 10,
        behavior: 'smooth'
    });
}

function stopScroll() {
    clearInterval(scrollInterval);
}

scrollLeftBtn.addEventListener('mousedown', () => {
    scrollInterval = setInterval(scrollLeft, 50);
});

scrollRightBtn.addEventListener('mousedown', () => {
    scrollInterval = setInterval(scrollRight, 50);
});

scrollLeftBtn.addEventListener('touchstart', () => {
    scrollInterval = setInterval(scrollLeft, 50);
});

scrollRightBtn.addEventListener('touchstart', () => {
    scrollInterval = setInterval(scrollRight, 50);
});

scrollLeftBtn.addEventListener('mouseup', stopScroll);
scrollRightBtn.addEventListener('mouseup', stopScroll);

scrollLeftBtn.addEventListener('touchend', stopScroll);
scrollRightBtn.addEventListener('touchend', stopScroll);

// Check overflow initially
function checkOverflow() {
    if (yearContainer.scrollWidth > yearContainer.clientWidth) {
        yearContainer.classList.add('overflow');
    } else {
        yearContainer.classList.remove('overflow');
    }
}

window.addEventListener('resize', checkOverflow);
document.addEventListener('DOMContentLoaded', checkOverflow);


</script>

@endpush

