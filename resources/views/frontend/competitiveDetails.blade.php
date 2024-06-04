@extends('layouts.app')
@section('main-content')
<section class="page-warpper-content" style="background-image:url('{{assets()}}images/background-image-2.jpg');">
   <div class="container">
      <div class="row content">
         <h1>{{$competitive->exam_name}}</h1>
      </div>
   </div>
</section>

<section class="competitite-exam">
   <div class="container">
      <div class="row">
         <div class="col-md-8">
            <div class="competitive-details">
               <h3 class="">{{$competitive->exam_name}}</h3>
               <p>Published On :{{ date("d M Y",strtotime($competitive->created_at))}}</p>
               <img src="{{url('public/image/exams/'.$competitive->image_name)}}" width="100%" >
               <div class="mt-3 description">
                  <p class="exam-details">
                     {!!$competitive->short_description!!}
                  </p>
                  {!!$competitive->description!!}
               </div>
            </div>
         </div>
         <div class="col-md-4">
            @if(count($pdfs))
            <div class="competitive-details-right">
               <h4>Pdf For {{$competitive->exam_name}}</h4>
               <ul class="list-group list-group-flush">
              
                @foreach($pdfs as $pdf)
                  <li class="list-group-item"><a target="_blank" href="{{url('/public/pdf/competitite_exam/'.$pdf->pdf_name)}}">{{ $loop->index + 1 }}&nbsp; &nbsp; {{$pdf->pdf_name}}</a></li>
                  @endforeach 
                 
                </ul>
            </div>
            @endif
            
            <div class="competitive-details-right">
              @if(count($competitives))
               <h4>Competitive Exam</h4>
               <ul class="list-group list-group-flush">
              
                @foreach($competitives as $row)
                  <li class="list-group-item"><a href="{{url('competitive/'.$row->slug)}}">{{$row->exam_name}}</a></li>
                  @endforeach
                 
                </ul>

              @endif
            </div>
         </div>
      </div>
   </div>
</section>
@endsection
@push('scripts')
@endpush