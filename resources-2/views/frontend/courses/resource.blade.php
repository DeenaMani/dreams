@extends('layouts.app')



@section('main-content')
<section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image-2.jpg');">
		<div class="container">
			<div class="row content">
				<h1>Resources</h1>
			</div>
		</div>
	</section>

	<section class="study-material">
		<div class="container">

			@if(count($categorys))

			@foreach($categorys as  $key => $category)
			<div class="resource-list">
				<h4 class="head"><span>{{$category->category_name}}</span></h4>

				@php
                   $courses  = App\Models\Course::where('category_id',$category->id)->get();
                  // pr($courses);
                @endphp
               @if(count($courses))
                 @foreach($courses as $key2 => $course)
				<div class="resource">



					<button class="course collapsed" data-bs-toggle="collapse" data-bs-target="#mainresource-{{$key2}}">
					<span>{{$course->course_name}}</span></button>
					
					<div id="mainresource-{{$key2}}" class="collapse body">
						@php
                           $topics  = App\Models\Topic::where('course_id',$course->id)->where('status',1)->where('type',1)->get();

                        @endphp


                         @if(count($topics))
                           

                        <div class="sub-resource">
							<button class="course collapsed" data-bs-toggle="collapse" data-bs-target="#resource-{{$course->id}}">
							<span>Video’s</span></button>
							<div id="resource-{{$course->id}}" class="collapse body">
								<ul class="list-unstyled course-content mb-0">
									 @foreach($topics as $key3 => $topic)  
									<li class="course-topic">
										<div class="course-title collapsed" data-bs-toggle="collapse" data-bs-target="#chapter-{{$topic->id}}">
											<span class="pe-2 lesson"><i class="fa-brands fa-youtube"></i></span>
											<span class="topic">{{$topic->topic}}</span>
											<span class="duration">{{$topic->duration}}</span>
										</div>

										@php
				                           $resources  = App\Models\Resource::where('topic_id',$topic->id)->get();

				                        @endphp
				                        	@if($resources)
										<div class="resource-materials collapse" id="chapter-{{$topic->id}}">
											@foreach($resources as $resource)
											<div class="row">
												<div class="col-lg-8 col-8">
													<p>{{$resource->title}}</p>
												</div>
												<div class="col-lg-4 col-4 text-end video">
													<a class="btn-link" data-bs-toggle="modal" data-bs-target="#exampleModal"  href="{{$resource->video_link}}" title="{{$resource->title}}">Preview</a>
												</div>
												
											</div>
											@endforeach
										</div>

											@endif
									</li>
									 @endforeach
								</ul>
							</div>
						</div>




                               
                           
                            @endif

                            						@php
                           $topics  = App\Models\Topic::where('course_id',$course->id)->where('status',1)->where('type',2)->get();

                        @endphp


                         @if(count($topics))
                           

                        <div class="sub-resource">
							<button class="course collapsed" data-bs-toggle="collapse" data-bs-target="#resource2">
							<span>Study Material’s</span></button>
							<div id="resource2" class="collapse body">
								<ul class="list-unstyled course-content mb-0">
									 @foreach($topics as $key3 => $topic)  
									<li class="course-topic">
										<div class="course-title collapsed" data-bs-toggle="collapse" data-bs-target="#study-material-{{$topic->id}}">
											<span class="pe-2 lesson"><i class="fa-brands fa-youtube"></i></span>
											<span class="topic">{{$topic->topic}}</span>
											<span class="duration">{{$topic->duration}}</span>
										</div>

										@php
				                           $resources  = App\Models\Resource::where('topic_id',$topic->id)->get();

				                        @endphp
				                        	@if($resources)
										<div class="resource-materials collapse" id="study-material-{{$topic->id}}">
											@foreach($resources as $resource)
											<div class="row">
												<div class="col-lg-8">
													<p>{{$resource->title}}</p>
												</div>
												<div class="col-lg-4 text-end video">
													<a class="image-popup-vertical-fit video-play btn-link" href="{{$resource->video_link}}" title="{{$resource->title}}">Preview</a>
												</div>
												
											</div>
											@endforeach
										</div>

											@endif
									</li>
									 @endforeach
								</ul>
							</div>
						</div>


						

                               
                           
                            @endif

						

						<div class="sub-resource">
							<button class="course collapsed" data-bs-toggle="collapse" data-bs-target="#paractice_test{{$key}}">
							<span>Practice Tests</span><span class="duration"></span></button>
							<div id="paractice_test{{$key}}" class="collapse body">
								<p class="text-center"><a href="{{url('login')}}" class="btn btn-theme-1">Login</a></p>
							</div>
						</div>
					</div>
				</div>	
					@endforeach
					@else
				<h5 class="">Coming Soon</h5>
			
				@endif
		

			</div>
			@endforeach

			@endif


			<!-- Modal -->
<div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Video 2 </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <video width="100%" height="400" controls>
				  <source src="{{assets('videos/movie.mp4')}}" type="video/mp4">
				  <source src="{{assets('videos/movie.ogg')}}" type="video/ogg">
				Your browser does not support the video tag.
				</video>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
       
      </div>
    </div>
  </div>
</div>


		</div>
	</section>
@endsection

@push('scripts')
@endpush