@extends('layouts.admin')

@push('css')
   <style>
   td  img {
        background-color: #199EFF;
        padding: 10px;
        border-radius: 6px;
    }
    </style>
@endpush

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h2 class="mb-1 font-weight-bold text-white">Our Values</h2>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card ">
                    <div class="card-header row">
                        <div class="col-lg-9">
                        <h4>Our Values</h4> 
                        </div>

                        <div class="col-lg-3">
                            <a href="{{url('admin/our-values/create')}}" class="btn btn-primary me-5" style="float:right;"> Add <i class="bx bx-plus"></i></a>
                        </div>
                    </div>                
                 
                    <div class="card-body">

                         @include('layouts.partials.messages')
                        <table class="table text-center mb-0">
                            <thead>
                                <tr>
                                    <th> Id </th>
                                    <th> Image </th>
                                    <th> Title </th>
                                    <th> Action </th>
                            </thead>
                        
                            <tbody>
                                @foreach ($results as $key => $data)
                                <tr>
                                    <td> {{$data->id}} </td>
                                    <td> <img src="{{url('public/image/our-values/'.$data->image)}}" width="50px"> </td>
                                    <td> {{$data->title}} </td>
                                    <td class="row justify-content-center"> 
                                        <a href="{{ url('admin/our-values/'.$data->id.'/edit') }}" class=" btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></a>
                                       <!--  <form action="{{ url('admin/our-values/'.$data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mx-3 btn btn-danger" onclick="return confirm('Are you sure to delete ?')"  ><i class="fa-solid fa-trash"></i></button> 
                                        </form> -->
                                    </td> 
                                </tr>
                                @endforeach
                            </tbody>
                        
                        </table>                  
                    </div>
                </div>
            </div>
        </div>
</div>
 @endsection