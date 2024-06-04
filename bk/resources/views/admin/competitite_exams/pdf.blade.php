@extends('layouts.admin')

@section('title','Imazine | Dashboard')

@section('main-content')
<div class="page-content">
        <div class="page-title-box">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-5 col-xl-6">
                        <div class="page-title">
                            <h3 class="mb-1 font-weight-bold">Add Competitive Exam Pdf</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         
        <div class="page-content-warpper mt--45">
            <div class="container-fluid">
                <div class="card mt--45">
                    <div class="card-header">
                    <h3>Add Competitive Exam Pdf</h3>
                    </div>
                    <div class="card-body text-dark">
                        @include('layouts.partials.messages')
                        
                            <form action="{{ url('admin/pdf_store') }}" method="post"  class="border-bottom mb-4" enctype="multipart/form-data">
                                @csrf
                                <input type="text" class="form-control mb-3" name="competitite_exams_id" value="{{$competitite_exam->id}}" hidden>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <label for="exam_name"> Exam Name </label>
                                        <input type="text" class="form-control mb-3" name="" value="{{$competitite_exam->exam_name}}" disabled readonly>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="pdf"> pdf </label>
                                        <input type="file" class="form-control mb-3" value="{{old('pdf_name')}}" name="pdf_name" required>
                                    </div>                                     
                                    
                                    <div class="col-lg-12 p-3 ">
                                        <input type="submit" class="btn btn-primary text-white" style="float:right; " value="Save Pdf">
                                    </div>
                                </div>                                 
                            </form>
                       

                        <table id="datatable" class="table border-top">
                            <thead>
                                <tr>
                                    <th>PDF</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($result as $key => $data)

                                <tr>
                                    <td> <a href="{{url('/public/pdf/competitite_exam/'.$data->pdf_name)}}" target="_blank">{{$data->pdf_name}}</td>
                                    <td class="row justify-content-center"> 
                                        <form action="{{ url('admin/delete-pdf/'.$data->id) }}" method="POST">
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


        