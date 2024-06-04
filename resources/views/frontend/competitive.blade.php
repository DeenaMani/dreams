@extends('layouts.app')
@section('main-content')
<section class="page-warpper-content" style="background-image:url('{{assets()}}images/background-image-2.jpg');">
        <div class="container">
            <div class="row content">
                <h1>List of different competitive exams for Children</h1>
            </div>
        </div>
    </section>
<section class="competitite-exam">
        <div class="container">

            @if(count($competitives))
            <h3 class="title my-4">Competitive Exams</h3>
            @foreach($competitives as $competitive)
            <div class="exam-list">
                <div class="row">
                    <div class="col-lg-3">
                        <img src="{{url('public/image/exams/'.$competitive->image_name)}}" width="100%" >
                    </div>
                    <div class="col-lg-8">
                        <h3 class="exam-title"><a href="{{url('competitive/'.$competitive->slug)}}">{{$competitive->exam_name}}</a></h3>
                        <p class="exam-details">
                            {!!$competitive->short_description!!}
                          
                        </p>
                        <p class="exam-publish">Published On :{{ date("d M Y",strtotime($competitive->created_at))}}</p>
                          <a href="{{url('competitive/'.$competitive->slug)}}">Read More</a>
                    </div>
                </div>
            </div>
            @endforeach
            @endif

            @if(count($others))
            <h3 class="title my-4">Other Exams</h3>
            @foreach($others as $other)
            <div class="exam-list">
                <div class="row">
                    <div class="col-lg-3">
                        <img src="{{url('public/image/exams/'.$other->image_name)}}" width="100%" >
                    </div>
                    <div class="col-lg-8">
                        <h3 class="exam-title"><a href="{{url('competitive/'.$other->slug)}}">{{$other->exam_name}}</a></h3>
                        <p class="exam-details">
                            {!!$other->short_description!!}
                          
                        </p>
                        <p class="exam-publish">Published On :{{ date("d M Y",strtotime($other->created_at))}}</p>
                          <a href="{{url('competitive/'.$other->slug)}}">Read More</a>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
            <!-- <div class="text-center">
                <a  href="contact.html" class="btn-theme-1">View More</a>
            </div>  -->
        </div>
    </section>
@endsection
@push('scripts')
@endpush