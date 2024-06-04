@extends('layouts.app')
@section('main-content')
    <section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image-2.jpg');">
        <div class="container">
            <div class="row content">
                <h1>Your Cart</h1>
            </div>
        </div>
    </section>

    <section class="cart">
        <div class="container">
          <table class="table">
            <thead>
              <tr>
                <th>Course</th>
                <th>Name</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            @if(count($cart))
            @php $total= 0;  @endphp
            @foreach($cart as $row)

            @php
                $cart_type = $row->cart_type ;
                if($cart_type == 1){
                     $category = App\Models\Category::where('id',$row->product_id)->where('status',1)->first();
              
                    $total += $category->price
             @endphp
              <tr>
                <td><img src="{{assets()}}image/category/{{$category->image}}" alt="{{$category->category_name}}"></td>
                <td>
                    <a href="{{url('courses/'.$category->category_slug)}}">{{$category->category_name}}</a><br>
                    <div class="row d-sm-none d-block mt-2">
                        <span>{{get_price($category->price)}}</span>
                        <span class="pull-right"><a href="{{url('cart-delete/'.base64_encode($row->id))}}"><i class="fa fa-trash"></i></a></span>
                    </div>
                </td>
                <td>{{get_price($category->price)}} </td>
                <td><a href="{{url('cart-delete/'.base64_encode($row->id))}}"><i class="fa fa-trash"></i></a></td>
              </tr>
              @php }  else{
                    $course= App\Models\Course::where('id',$row->product_id)->where('status',1)->first();
                     $total += $course->price
                @endphp
                 <tr>
                <td><img src="{{assets()}}image/course/{{$course->course_image}}" alt="{{$course->coursename}}"></td>
                <td>
                    <a href="{{url('course/'.$course->slug)}}">{{$course->course_name}}</a><br>
                    <div class="row d-sm-none d-block mt-2">
                        <span>{{get_price($course->price)}}</span>
                        <span class="pull-right"><a href="{{url('cart-delete/'.base64_encode($row->id))}}"><i class="fa fa-trash"></i></a></span>
                    </div>
                </td>
                
                    <td>{{get_price($course->price)}} </td>
                    <td><a href="{{url('cart-delete/'.base64_encode($row->id))}}"><i class="fa fa-trash"></i></a></td>
                  </tr>
              @php } @endphp
            @endforeach
              @else
              <tr><td align="center" colspan="4">No Cart Found</td></tr>
              @endif
            </tbody>
          </table>
            @if(count($cart) > 0 )
          <div class="row">
            <div class="col-lg-6">
                <a href="{{url('courses')}}" class="btn-theme-1"> Add More Course <i class="fa fa-plus"></i></a>
            </div>
            <div class="col-lg-6">
                <div class="total">
                    <ul class="list-unstyled">
                        <li class="sub-total">Total :   {{get_price($total)}}</li>
                    </ul>
                    <a href="{{url('checkout')}}" class="btn-theme-1"><i class="fa fa-lock"></i> Proceed To checkout</a>
                </div>
            </div>
          </div>
            @endif
        </div>
    </section>

@endsection
@push('scripts')
@endpush