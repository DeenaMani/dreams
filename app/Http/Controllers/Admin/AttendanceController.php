<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendance = Attendance::all();

        return view('admin.attendance.index',compact('attendance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all();
        return view ('admin.attendance.create',compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request ->all();

        $attendance = new Attendance();
        $attendance = Attendance::create($data);

        return back()->with('success',"Added Successfully");
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

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }

    public function absent()
    {
        return view('admin.attendance.absent');
    }

    public function getAbsentMembers(Request $request)
    {

    //    // Get the current month's start and end dates
    //     $currentMonthStart = now()->startOfMonth();
    //     $currentMonthEnd = now()->endOfMonth();

    //     // Get the names of all users
    //     $allUsers = Member::pluck('name', 'id');

    //     // Retrieve the attendance data for the current month for all users
    //     $attendanceData = Attendance::whereIn('member_id', $allUsers->keys())
    //         ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
    //         ->get();

    //     // Initialize an array to store absent users and their absent dates
    //     $absentUsers = [];

    //     // Iterate through each user
    //     $allUsers->each(function ($userName, $userId) use ($attendanceData, $currentMonthStart, $currentMonthEnd, &$absentUsers) {
    //         $absentDates = [];

    //         // Iterate through each day of the current month
    //         for ($date = clone $currentMonthStart; $date <= $currentMonthEnd; $date->addDay()) {
    //             $attendance = $attendanceData->where('member_id', $userId)
    //                 ->where('date', $date->format('Y-m-d'))
    //                 ->first();

    //             // If the user's attendance is not recorded for this date, they are absent
    //             if (!$attendance) {
    //                 $absentDates[] = $date->format('Y-m-d');
    //             }
    //         }

    //         // If the user was absent on one or more dates, record their name and absent dates
    //         if (!empty($absentDates)) {
    //             foreach ($absentDates as $absentDate) {
    //                 $absentUsers[] = [
    //                     'member_id' => $userId,
    //                     'name' => $userName,
    //                     'date' => $absentDate
    //                 ];
    //             }
    //         }
    //     });

    //     // Sort the absent users' data by date in ascending order
    //     $sortedAbsentUsers = collect($absentUsers)->sortBy('date')->values()->all();

    //     return response()->json([
    //         'data' => $sortedAbsentUsers
    //     ]);

    $minAttendanceDate = Attendance::min('date');
    
    $minAttendanceDate = $minAttendanceDate ? Carbon::parse($minAttendanceDate) : now();
    
    $fromDate = $request->input('fromDate');
    $toDate = $request->input('toDate');
   
    if ($fromDate) {
        $parsedFromDate = Carbon::parse($fromDate);
        $currentMonthStart = $parsedFromDate->lt($minAttendanceDate) ? $minAttendanceDate : $parsedFromDate;
    } else {
        $currentMonthStart = $minAttendanceDate;
    }
    
    if ($toDate) {
        $parsedToDate = Carbon::parse($toDate);
        $currentMonthEnd = $parsedToDate->gt(now()) ? now() : $parsedToDate;
    } else {
        $currentMonthEnd = now();
        
    }
    
    $allUsers = Member::pluck('name', 'id');
    
    $attendanceData = Attendance::whereIn('member_id', $allUsers->keys())
        ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
        ->get();
    
    $absentUsers = [];
    
    $allUsers->each(function ($userName, $userId) use ($attendanceData, $currentMonthStart, $currentMonthEnd, &$absentUsers) {
        $absentDates = [];
    
        for ($date = clone $currentMonthStart; $date <= $currentMonthEnd; $date->addDay()) {
            $attendance = $attendanceData->where('member_id', $userId)
                ->where('date', $date->format('Y-m-d'))
                ->first();

            if (!$attendance) {
                $absentDates[] = $date->format('Y-m-d');
            }
        }
    
        if (!empty($absentDates)) {
            foreach ($absentDates as $absentDate) {
                $absentUsers[] = [
                    'member_id' => $userId,
                    'name' => $userName,
                    'date' => $absentDate
                ];
            }
        }
    });
    
    $sortedAbsentUsers = collect($absentUsers)->sortBy('date')->values()->all();
    
    return response()->json([
        'data' => $sortedAbsentUsers
    ]);

        
    }
}
