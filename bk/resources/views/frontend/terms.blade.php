@extends('layouts.app')
@section('main-content')

<section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image-2.jpg');">
        <div class="container">
            <div class="row content">
                <h1>{{$content->title}}</h1>
            </div>
        </div>
    </section>
    <section class="about about-page bg-white">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {!!$content->full_description!!}
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
@endpush