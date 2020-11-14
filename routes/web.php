<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', function (){
  return view('home');
})->middleware('auth')->name('home');


Route::get('/stripe-payment', 'PaymentController@view')->name('stripe.payment');
Route::post('/payment', 'PaymentController@payment')->name('payment');
Route::get('/payments', 'HomeController@payments')->name('payments');
