<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use App\Models\Course;
use App\Models\Student;
use App\Models\Instructor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchase = BookingData::where('booking_status',1)->count();
        $total_amount = BookingData::where('booking_status',1)->sum('total_price');
        $student  = student::count();
        $teacher  = Instructor::where('instructor_type',1)->count();
        $mentor  = Instructor::where('instructor_type',2)->count();
        return view ('admin.dashboard',compact('purchase','student','teacher','mentor','total_amount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
