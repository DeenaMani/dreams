<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $results = Purchase::select('purchases.*','students.first_name')
                    ->leftJoin('students', 'students.id', '=', 'purchases.student_id')
                    ->orderByDesc('purchases.id')
                    
                    ->get();

        $results->transform(function ($result) {
            $result->payment_method = $this->payment($result->payment_method);
            return $result;
        });

        $statusMapping = [
            0 => 'completed',
            1 => 'failed',
            2 => 'process'
        ];
    
        $results->transform(function ($result) use ($statusMapping) {
            $result->status = $statusMapping[$result->status] ?? 'Unknown';
            return $result;
        });

        foreach ($results as $data) {
            $data->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $data['created_at'])->format('d-m-y  h:i A');
        }

        return view ('admin.purchase.index',compact('results'));
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

       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $result = Purchase::select('purchases.*','purchase_courses.*','students.first_name','students.last_name','students.email','students.phone')
        ->leftJoin('students', 'students.id', '=', 'purchases.student_id')
        ->leftJoin('purchase_courses', 'purchase_courses.purchase_id', '=', 'purchases.id')
        ->find($id);

        $course = Course::select('courses.*','categories.*')
        ->leftjoin('categories','categories.id','=','courses.category_id')
        ->where('courses.id',$result->course_id)
        ->first();


        return view ('admin.purchase.invoice',compact('result','id','course'));
        
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

    public function status(Request $request, string $id)
    {
        $status = Purchase::find($id);
        $status->status =  $request->status;
        $status->save();

        echo json_encode(array("status" => 1));
    }

    public function payment($status)
    {
        switch ($status) {
            case 1:
                return 'Card Payment';
            case 2:
                return 'Paytm';
            case 3:
                return 'Pay Pal';
            default:
                return 'Unknown';
        }
    }
}
