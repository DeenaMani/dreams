@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Gallery</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add Gallery</h1>
                    </div>
                    <div class="card-body text-dark">
                        @include('layouts.partials.messages')
                        <form action="{{ url('admin/gallery-store') }}" method="post"  class="border-bottom mb-4" enctype="multipart/form-data">
                            @csrf
                            <input type="text" class="form-control mb-3" name="gallery_id" value="{{$gallery->id}}" hidden>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="year"> Gallery Year </label>
                                    <input type="text" class="form-control mb-3" name="" value="{{$gallery->year}}"  disabled readonly>
                                </div>

                                <div class="col-lg-6">
                                    <label for="year"> Gallery Type </label>
                                    <select class="form-select form-control mb-4"  name="" disabled>
                                        <option selected disabled>Select Gallery Type</option>
                                        <option value="1" {{$gallery->type == '1' ? 'selected' : ''}}>Photo</option>
                                        <option value="2" {{$gallery->type == '2' ? 'selected' : ''}}>Video</option>
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label for="title"> Gallery Title </label>
                                    <input type="text" class="form-control mb-3" name="title" required>
                                </div>  

                                <div class="col-lg-6">
                                    <label for="image"> Galley Image </label>
                                    <input type="file" class="form-control mb-3" name="image">
                                </div>

                                <div class="col-lg-12 video_link d-none">
                                    <label for="video_link"> video Link</label>
                                    <input type="text" class="form-control mb-3" name="video_link">
                                </div>   
                               
                                <div class="col-lg-12 p-3 ">
                                    <input type="submit" class="btn btn-primary text-white" style="float:right; " value="Add Gallery">
                                </div>
                            </div>                                   
                        </form>

                        <table id="datatable" class="table border-top">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th class="video_link d-none">Video Link</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $key => $data)
                                <tr>
                                    <td> {{$data->title}} </td>
                                    <td> <img src="{{url('public/image/gallery/'.$data->image)}}" width="100px"> <br>  </td>
                                    <td class="video_link d-none"> <a href="{{$data->video_link}}" target="_blank">{{$data->video_link}}</td>
                                    <td class="row justify-content-center"> 
                                        <form action="{{ url('admin/delete-resource/'.$data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="mx-1 btn btn-danger" onclick="return confirm('Are you sure to delete ?')" ><i class="fa fa-trash"></i></button> 
                                        </form>
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

@push('scripts')

<script>
    $(document).ready(function(){
        
        if($("select").val() == 2) {
            $(".video_link").removeClass("d-none"); 
        } else {
            $(".video_link").addClass("d-none"); 
        }
       
        $("select").change(function(){
            
            if($(this).val() == 2) { 
                $(".video_link").removeClass("d-none"); 
            } else {
                $(".video_link").addClass("d-none");
            }
        });
    });
</script>

@endpush
        