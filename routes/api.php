<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('emails/{email}', '\App\Actions\GetEmailAction')->name('emails.view');
Route::get('emails', '\App\Actions\EmailsListAction')->name('emails.list');
Route::post('emails', '\App\Actions\NewEmailAction')->name('emails.new');
