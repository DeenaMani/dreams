@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Terms</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add Terms</h1>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/terms') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-6">

                                <div class="col-lg-12">
                                    <div class="row">
                                        <label for="title"> Title</label>
                                        <input type="text" class="form-control mb-3" name="title" required>
                                    </div>
                                </div>


                                 <div class="col-lg-12">
                                    <div class="row">
                                        <label for="full_description"> terms Description </label>
                                        <textarea  class="form-control mb-3" id="summernote-basic" name="full_description" value=""></textarea>
                                    </div>
                                </div>  
                               
                                <div class="row">
                                    <div class="col-lg-12 p-3 ">
                                        <input type="submit" class="btn btn-primary text-white" value="Save terms">
                                    </div>
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
<script type="text/javascript">
    $(document).ready(function(){
        $(".toggle-class").change(function(){

            if ($(this).is(":checked")) {

                $(".price_div").show();
            }
            else{
                $(".price_div").hide();
                $(".price_div").find("input").val("");
            }
        })
    })
</script>
@endpush