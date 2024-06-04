@extends('layouts.app')
@section('main-content')

<section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image-2.jpg');">
        <div class="container">
            <div class="row content">
                <h1>FAQ</h1>
            </div>
        </div>
    </section>
    <section class=" about-page bg-white">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                   <section class="faqs pt--60 pb--60">
        <div class="container-xl">
    
                <h1 class="text-center mb-5">Recently Asked FAQ's</h1>  

                <div class="row  justify-content-center">
                    <div class="col-lg-9 faq-data">

                        @if($contents)
                        @foreach($contents as $key => $row)
                        <div class="faqs-section">
                            <div class="card" role="tablist">
                                <a href="#" class=" card-header title" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}">
                                  <h6> {{$row->faq_title}}</h6>
                                </a>
                                <div id="collapse{{$key}}" class="collapse card-body"  data-parent="#accordion">
                                    {!!html_entity_decode($row->faq_description)!!}
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif

                    </div> 
                </div>
        </div>
    </section>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
@endpush