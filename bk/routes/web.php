<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;

Route::get('/',[HomeController::class,'index']);
Route::get('/competitive',[HomeController::class,'competitive']);
Route::get('/competitive/{slug}',[HomeController::class,'competitiveDetails']);
Route::get('/schedules',[HomeController::class,'schedules']);
Route::get('/contact-us',[HomeController::class,'contact']);
Route::get('/book-session',[HomeController::class,'bookSession']);
Route::get('/about-us',[HomeController::class,'about']);
Route::get('/privacy-policy',[HomeController::class,'pages']);
Route::get('/terms-conditions',[HomeController::class,'pages']);
Route::get('/faq',[HomeController::class,'faq']);
Route::post('/contact-post',[HomeController::class,'contactpost'])->name('contactpost');
Route::post('/free-session',[HomeController::class,'freesession'])->name('freesession');
Route::post('/enquiry',[HomeController::class,'enquiry'])->name('enquiry');
Route::post('/feedback',[HomeController::class,'feedback'])->name('feedback');
//Course Controller
Route::get('/courses',[CoursesController::class,'index']);
Route::get('/study-material',[CoursesController::class,'resource']);
Route::get('/courses/{slug}',[CoursesController::class,'courseDetails']);
Route::get('/courses/{slug}/{slug2}',[CoursesController::class,'courseDetails']);
//Cart
Route::get('/cart',[CartController::class,'index']);
Route::post('/add-to-cart',[CartController::class,'addToCart'])->name('addToCart');
Route::get('/cart-delete/{id}',[CartController::class,'cartDelete']);
Route::get('/checkout',[CartController::class,'checkout']);
Route::post('/checkoutPost',[CartController::class,'checkoutPost']);
//User
Route::get('/login',[UserController::class,'login']);
Route::post('/login-post',[UserController::class,'loginPost'])->name('loginPost');
Route::get('/register',[UserController::class,'register']);
Route::post('/register-post',[UserController::class,'registerPost'])->name('registerPost');
Route::get('/forget-password',[UserController::class,'forgetPassword']);
Route::post('/forget-password-post',[UserController::class,'forgetPasswordPost'])->name('forgetPasswordPost');
Route::post('/save-profile',[UserController::class,'saveProfile'])->name('saveProfile');
Route::get('/dashboard',[UserController::class,'dashboard']);
Route::get('/profile',[UserController::class,'profile']);
Route::get('/transaction',[UserController::class,'transaction']);
Route::get('/change-password',[UserController::class,'changePassword']);
Route::post('/change-password-post',[UserController::class,'changePasswordPost'])->name('changePasswordPost');
Route::get('/logout',[UserController::class,'logout']);

// Route::get('product',[RazorpayController::class,'index']);
Route::post('payment-gateway',[PaymentController::class,'razorPaystore'])->name('razorpay.payment.store');
Route::get('/confirmation/{id}',[PaymentController::class,'confirmation'])->name('confirmation');
Route::get('/failed/{id}',[PaymentController::class,'failed'])->name('failed');
Route::post('payment-gateway',[PaymentController::class,'paymentUpdate'])->name('paymentUpdate');
