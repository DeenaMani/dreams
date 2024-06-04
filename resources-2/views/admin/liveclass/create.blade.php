@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Live Class</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h1>Add Live Class</h1>
                    </div>
                    <div class="card-body text-dark">
                         @include('layouts.partials.messages')
                        <form action="{{ url('admin/live-class') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                <div class="col-lg-6">
                                    <label for="Category_id"> Category </label> <br>
                                    <select name="category_id" class="form-control mb-4" id="category" required>                               
                                        <option selected disabled> Select Category </option>
                                            @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->category_name}}</option>
                                            @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label for="course_id"> Course </label> <br>
                                    <select name="course_id" id="course" class="form-control ">                               
                                        <option selected disabled>Select Course</option>
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label for="topic"> Topic </label> <br>
                                    <input name="topic" type="text" class="form-control  mb-4" required>                               
                                </div>

                                <div class="col-lg-6">
                                    <label for="exam_type"> Exam Type </label>
                                    <input type="text" class="form-control mb-3" name="exam_type" required>
                                </div>  

                                <div class="col-lg-6">
                                    <label for="date"> Meeting Date</label>
                                    <input type="date" class="form-control mb-3" name="date" required>
                                </div>  

                                <div class="col-lg-6">
                                    <label for="time"> Meeting Time </label>
                                    <input type="time" class="form-control mb-3" name="time" required>
                                </div>  

                                <div class="col-lg-12">
                                    <label for="meeting_link"> Meeting Link </label>
                                    <input type="text" class="form-control mb-3" name="meeting_link" required>
                                </div>

                                <div class="col-lg-12">
                                    <label for="additional_information"> Additional Information </label>
                                    <textarea type="text" class="form-control mb-3" name="additional_information"></textarea>
                                </div>  

                                <div class="col-lg-12 p-3 ">
                                    <input type="submit" class="btn btn-primary text-white" value="Save Class">
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


$('#category').change(function() {
        var id = $(this).val();
        var courseSelect = $('#course');
        if (id !== '') {
        
            $.ajax({
                url: "{{URL('/')}}/admin/get-course/" + id ,
                method: 'GET',
                data: { category_id: id },
                success: function(data) {
                    // Update the course dropdown options
                    var courseSelect = $('#course');
                    courseSelect.empty();
                    courseSelect.append($('<option>').val('').text('Select course').prop('disabled', true).prop('selected', true));
                    $.each(data, function(index, course) {
                        courseSelect.append($('<option>').val(course.id).text(course.course_name));
                    });
                }
            });
        } else {
            courseSelect.empty();
        }
    });

</script>
@endpush