@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Category</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add Category</h1>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/category') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="col-lg-6">

                                <div class="col-lg-12">
                                    <div class="row">
                                        <label for="image"> Category Image </label>
                                        <input type="file" class="form-control mb-3" name="image" required>
                                    </div>
                                </div>  

                                <div class="col-lg-12">
                                    <div class="row">
                                        <label for="category_name"> Category Name </label>
                                        <input type="text" class="form-control mb-3" name="category_name" required>
                                    </div>
                                </div>



                                 <div class="col-lg-12">
                                    <div class="row">
                                        <label for="category_name"> Category  Short Description </label>
                                        <textarea  class="form-control mb-3"  name="category_description" value=""></textarea>
                                    </div>
                                </div>  


                                 <div class="col-lg-12">
                                    <div class="row">
                                        <label for="category_name"> Category  Full Description </label>
                                        <textarea  class="form-control mb-3" id="summernote-basic" name="category_full_description" value=""></textarea>
                                    </div>
                                </div>  

                                  <div class="col-lg-12 mt-5">
                                    <div class="row">
                                        <label for="category_name"> Multiple Course </label>
                                      <div class="form-check form-switch">
                                            <input class="toggle-class" type="checkbox" name="is_mutiple" value="1" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" 
                                            data-on="Active" data-off="InActive" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 mt-3 price_div" style="display: none;">
                                    <div class="row">
                                        <label for="old_price"> Price </label>
                                        <input type="text" class="form-control mb-3" name="old_price" id="old_price">
                                    </div>
                                </div>


                                <div class="col-lg-12 price_div" style="display: none;">
                                    <div class="row">
                                        <label for="category_name"> Price </label>
                                        <input type="text" class="form-control mb-3" name="price">
                                    </div>
                                </div>

                              
                                <div class="col-lg-12 price_div" style="display: none;">$category->is_mutiple  == 1 ? '' :  'none' }} " >
                                    <div class="row">
                                        <label for="category_name"> Validity Days </label>
                                        <input type="number" class="form-control mb-3" name="validity_days" value="{{old('validity_days')}}">
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-lg-12 p-3 ">
                                        <input type="submit" class="btn btn-primary text-white" value="Save Category">
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