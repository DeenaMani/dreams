@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Edit Clients</h3>
                        </div>
                    </div>
                </div>
            </div>
       </div>

        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-header">
                        <h4>Edit Clients</h4>
                    </div>
                    <div class="card-body">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/client/'.$client->id) }}" method="post" enctype="multipart/form-data" >
                            @csrf
                            @method('PUT')
                            
                            <div class="row">

                                <div class="col-lg-6">
                                    <label for="first_name"> First Name </label>
                                    <input type="text" class="form-control mb-3" name="first_name" value="{{$client->first_name}}">
                                </div>

                                <div class="col-lg-6">
                                    <label for="middle_name"> Middle Name </label>
                                    <input type="text" class="form-control mb-3" name="middle_name"value="{{$client->middle_name}}">
                                </div>

                                <div class="col-lg-6">
                                    <label for="last_name"> Last Name </label>
                                    <input type="text" class="form-control mb-3" name="last_name"  value="{{$client->last_name}}">
                                </div>

                                <div class="col-lg-6">
                                    <label for="email"> Email Address </label>
                                    <input type="text" class="form-control mb-3" name="email"  value="{{$client->email}}">
                                </div>

                                <div class="col-lg-6">
                                    <label for="phone"> Phone Number </label>
                                    <input type="text" class="form-control mb-3" name="phone"  value="{{$client->phone}}">
                                </div>


                                <div class="col-lg-6 mb-3">
                                    <label for="Country"> Country </label>
                                    <select id="country" name="country_id" class="form-select form-control" value="">
                                        <option  disabled selected>Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{$country->id}}" {{ $client->country_id == $country->id ? 'selected' : '' }} >{{$country->country_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="state"> Select State </label>
                                    <select id="state" name="state_id" class="form-select form-control" value="">
                                        <option disabled selected>Select State</option>
                                        @foreach($states as $state)
                                        <option  value="{{$state->id}}" {{ $client->state_id == $state->id ? 'selected' : '' }}>{{$state->state_name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="district"> District </label>
                                    <input class="form-control" type="text" name="district" value="{{$client->district}}">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="place"> Place </label>
                                    <input class="form-control" type="text" name="place" value="{{$client->place}}">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="pincode"> Post Code </label>
                                    <input class="form-control" type="text" name="pincode" value="{{$client->pincode}}">
                                </div>

                                <div class="col-lg-12 p-3 ">
                                    <input type="submit" class="btn btn-primary text-white" style="float:right;" value="Update Instructor">
                                </div>
                            </div> 
                        </form>
                    </div>
                </div> 
            </div> 
        </div>

</div> 
@endsection

 
@push('scripts')

    <script>
        $('#country').change(function() {
            var id = $(this).val();
            var stateSelect = $('#state');
            if (id !== '') {
            
                $.ajax({
                    url: "{{URL('/')}}/admin/get-state/" + id ,
                    method: 'GET',
                    data: { country_id: id },
                    success: function(data) {
                        // Update the state dropdown options
                        var stateSelect = $('#state');
                        stateSelect.empty();
                        stateSelect.append($('<option>').val('').text('Select State').prop('disabled', true).prop('selected', true));
                        $.each(data, function(index, state) {
                            stateSelect.append($('<option>').val(state.id).text(state.state_name));
                        });
                    }
                });
            } else {
                stateSelect.empty();
            }
        });

    </script>

@endpush