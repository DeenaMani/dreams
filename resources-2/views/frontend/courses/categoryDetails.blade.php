@extends('layouts.app')

@section('main-content')

<section class="course-details">
        <section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image.jpg');" >
            <div class="container">
                <div class="row content">
                    <div class="col-lg-8">
                        <h2 class="title">{{$category->category_name}}</h2>
                        <p> {!!$category->category_description!!}</p>
                            <!-- <div class="details">
                                <ul class="list-unstyled">
                                    <li><i class="fa fa-user pe-2"></i> 34,455 Enrolled</li>
                                    <li><div class="rating">
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        <i class="fa-regular fa-star"></i> <span class="ps-2"> (3.0) </span>
                                    </div></li>
                                </ul>
                            </div> -->
                    </div>
                </div>
            </div>
        </section>
        <div class="description">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="">
                         
                            <style type="text/css">
                                
                            </style>
                @if($courses)
                <h3 class=" mb-3 course_title">Subject Wise Topics</h3>
                <nav>
                    <div class="nav nav-tabs nav-justified mb-3 mt-5" id="nav-tab" role="tablist">
                         @foreach($courses as $key => $course)
                        <button class="nav-link {{$course->slug == $slug2  || ($slug2 == '' && $key ==0) ? 'active ' : ''}}" id="nav-{{$course->id}}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{$course->id}}" type="button" role="tab" aria-controls="nav-{{$course->id}}" aria-selected="true">{{$course->course_name}}</button>
                        @endforeach
                    </div>
                </nav>
                
                
                <div class="tab-content " id="nav-tabContent">
                    @foreach($courses  as $key =>  $course)
                    <div class="tab-pane fade {{$course->slug == $slug2 || ($slug2 == '' && $key ==0)  ? 'active show' : ''}}" id="nav-{{$course->id}}" role="tabpanel" aria-labelledby="nav-{{$course->id}}-tab">

                    @if($course->description)
                        <p>{!!$course->description!!}</p>
                    @endif

                          @php
                                   $topics  = App\Models\Topic::where('course_id',$course->id)->where('type',1)->get();

                                @endphp


                       <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th scope="col" width="10%">#</th>
                                  <th scope="col">Topics</th>
   
                                </tr>
                              </thead>
                              <tbody>
                            @if(count($topics))
                                @foreach($topics as $key => $topic)  
                                <tr>
                                  <th scope="row">{{$key+ 1}}</th>
                                  <td>{{$topic->topic}}</td>
                                </tr>
                                @endforeach
                            @else
                            <tr><th colspan="2" class="text-center-=1">Coming Soon</th></tr>
                            @endif

                                
                              </tbody>
                            </table>
                    </div>
                     @endforeach
                </div>
               
                @endif

                        </div>


                        <div class="overview border-bottom mt-5">
                            <div class="title h3 mb-3">About The Course</div>
                            {!!$category->category_full_description!!}
                        </div>
                    
                       
                    </div>
                  
                    <div class="col-lg-4">
                        <aside class="sidebar">
                            <div class="course-specification">
                                <div class="iamge">
                                    <img src="{{assets()}}/image/category/{{$category->image}}" width="100%">
                                </div>

                                <div class="course-spec-details">
                                    <div class="price">
                                        {{get_price($category->price)}}  <!--<del>₹ 1500</del> -->
                                        <!-- <span class="offer">13% OFF</span> -->
                                    </div>
                                    <div class="add-to-cart">
                                        <a class="btn-theme-1 add_to_cart" data-id="{{$category->id}}" data-type="1" href="javascript:void(0)"  > <i class="fa fa-cart-shopping pe-3"></i> Add To Cart</a>
                                    </div>

                             
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    $(".add_to_cart").on("click",function(){

        var id = $(this).data('id');
        var type = $(this).data('type');
        $.ajax({
           type:'POST',
           url:'{{route("addToCart")}}',
           dataType: 'json',
           data: {'_token' : '{{csrf_token()}}' ,  type : type , id : id },
           success:function(data) {
            $(".cart-count").html(data.totalCount);
           // console.log(data);
                if(data.status == 1){
                    $.notify(data.message, "success");
                }   
                else{
                    $.notify(data.message, "warn");
                }
              
           }
        });
    });
</script>
@endpush