@extends('layouts.app')

@section('main-content')
<section class="page-warpper-content" style="background-image:url('{{assets()}}/images/background-image-2.jpg');">
        <div class="container">
            <div class="row content">
                <h1>Live Classes and Q&A Sessions</h1>
            </div>
        </div>
    </section>

    <section class="schedules">
        <div class="container">
            <div class="schedules-tables">
                <div class="row align-items-center mb-2">
                    <div class="col-md-6">
                        <!-- <h4>Class Schedules</h4> -->
                    </div>
                    <div class="col-md-6">
                        <!-- <ul class="list-unstyled text-end">
                            <li class="d-inline-block">
                                <label for="search" class="d-flex align-items-center">Search: <input type="text" name="search" class="form-control d-inline-block ms-2" placeholder="Search List"></label>
                            </li>
                        </ul> -->
                    </div>
                </div>
                <div class="shedule-table overflow-auto">
                    <table class="table mb-0 table-bordered">
                        <thead>
                            <tr>
                                <th>Exam Type</th>
                                <th>Subject</th>
                                <th>Topic</th>
                                <th>Meeting Link</th>
                                <th>Date & Time</th>
                                <th>Additional Information</th>
                            </tr>
                        </thead>

                        <tbody>
                             @if(count($schedules))
                                @foreach($schedules as $schedule)
                                    <tr>
                                        <td>{{$schedule->exam_type}}</td>
                                        <td>{{$schedule->topic}}</td>
                                        <td>{{$schedule->course_name}}</td>
                                        <td><a target="_blank" href="{{$schedule->meeting_link}}">{{$schedule->meeting_link}}</a></td>
                                        <td>{{$schedule->class_date}}</td>
                                        <td>{{$schedule->additional_information}}</td>
                                    </tr>
                                @endforeach
                            @else
                            <tr><td colspan="6" align="center">No Live Class</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
               <!--  <div class="row align-items-center pt-4 pb-2">
                    <div class="col-md-6">
                        Showing 1 to 3 out of 10
                    </div>
                    <div class="pagination col-md-6 m-0">
                        <ul class="list-unstyled ms-auto">
                            <li><a href="" class=""><i class="fa fa-angle-left"></i></a></li>
                            <li><a href="" class="active">1</a></li>
                            <li><a href="" class="">2</a></li>
                            <li><a href="" class="">3</a></li>
                            <li><a href="" class=""><i class="fa fa-angle-right"></i></a></li>
                        </ul>
                    </div>
                </div> -->
            </div>
        </div>
    </section>
@endsection
