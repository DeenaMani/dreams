@extends('layouts.app')
@section('main-content')
@php
    $setting = cache('settings');
@endphp
<div class="container ">
    <div class="my-5 ">
        <h3  class="text-center">Don't refresh page</h3>
        <p class="text-center">Payment is processing</p>
    </div>
</div>
<div  style="display:none">
    <form method="post" id="paymentCard" action="{{route('razorpay.payment.store')}}">
             @csrf
             
        <script src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="{{ env('RAZORPAY_KEY') }}"
                data-amount="{{$booking->total_price * 100}}"
                data-buttontext="Pay {{$booking->total_price }} INR"
                data-name="{{$setting->company_name}}"
                data-description="{{$setting->company_name}}"
                data-image="{{assets()}}image/setting/{{$setting->logo}}" 
                data-prefill.name="Pandiyan"
                data-prefill.email="gpandiyan.tech@gmail.com"
                data-theme.color="#0495FF">
        </script>
        <input type="hidden" id="bookingId" name="bookingId" value="{{$booking_id}}">
    </form>
</div>
@endsection
@push('scripts')
<script type="text/javascript">

    setTimeout(
  function() 
  {
     $("#paymentCard").submit()
  }, 2000);
    
</script>
   
@endpush