<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CompetititeExamsController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\LiveclassController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\OurvalueController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TermsController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\WhychooseController;
use App\Http\Controllers\Admin\BookingsController;



Route::get('/login',[Logincontroller::class,'show'])->name('login.show');
Route::post('/login',[Logincontroller::class,'login'])->name('admin.login.perform');
   
    

Route::group(['middleware' => ['auth:web'] ],function(){
    //Logout
    Route::get('/logout',[LoginController::class,'logout']);

    //change Password
    Route::get('/change-password',[LoginController::class,'password']);
    Route::post('/change-password',[LoginController::class,'change_password'])->name('admin.password.change');

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/bookings', [BookingsController::class, 'index']);
    Route::get('/bookings/{id}/view', [BookingsController::class, 'view']);
    Route::get('/bookings/booking-status/{id}/{status}', [BookingsController::class, 'bookingStatus']);
    Route::get('/bookings/payment-status/{id}/{status}', [BookingsController::class, 'paymentStatus']);
    //Setting
    Route::resource('/setting',SettingController::class);

    //Banner
    Route::resource('/banner',BannerController::class);

    //About
    Route::resource('/about',AboutController::class);

    //Faq
    Route::resource('/faq',FaqController::class);
    Route::get('/faq/status/{id}/{status}',[FaqController::class, 'status']);

    //why Choose us
    Route::resource('/whychoose',WhychooseController::class);
    Route::get('/whychoose/status/{id}/{status}',[WhychooseController::class, 'status']);

    //why Choose us
    Route::resource('/our-values',OurvalueController::class);

    //Categories
    Route::resource('/category',CategoryController::class);
    Route::get('/category/status/{id}/{status}',[CategoryController::class, 'status']);

    //Topic 
    Route::resource('/topic',TopicController::class);
    Route::get('/topic/status/{id}/{status}',[TopicController::class, 'status']);
    Route::get('/topic/resource-status/{id}/{status}',[TopicController::class, 'resourceStatus']);
    Route::get('/get-topic/{id}', [TopicController::class, 'gettopic']);
    route::get('/topic/{id}/addresource',[TopicController::class,'addresource']);
    Route::post('/resource_store', [TopicController::class,'resource_store']);
    //   Route::post('/store-resource', [TopicController::class,'resource_store'])->name('resource.store');
    Route::get('/store-resource', [TopicController::class,'resource_get'])->name('resource.get');
    Route::delete('/delete-resource/{id}', [TopicController::class,'delete'])->name('resource.destroy');

    //course
    Route::resource('/course',CourseController::class);
    Route::get('/course/status/{id}/{status}',[CourseController::class, 'status']);
    Route::get('/get-course/{id}', [CourseController::class, 'getcourse']);
    //  Route::get('/get-courses', [CourseController::class, 'getcourse'])->name('get.courses');

    //instructor
    Route::resource('/instructor',InstructorController::class);
    Route::get('/instructor/status/{id}/{status}',[InstructorController::class, 'status']);

    //instructor
    Route::resource('/live-class',LiveclassController::class);
    Route::get('/live-class/status/{id}/{status}',[LiveclassController::class, 'status']);

    //Competitite Exam
    Route::resource('/competitite-exam',CompetititeExamsController::class);
    Route::get('/competitite-exam/status/{id}/{status}',[CompetititeExamsController::class, 'status']);

    //Students
    Route::resource('/student',StudentController::class);
    Route::get('/student/status/{id}/{status}',[StudentController::class, 'status']);

    //terms
    Route::resource('/terms',TermsController::class);

});
