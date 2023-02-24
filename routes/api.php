<?php

use App\Http\Controllers\SendMailController;
use App\Mail\MyTestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('send-mail', [SendMailController::class, 'sendMail']);

Route::get('/testroute', function() {
    $name = "Funny Coder";

    Mail::to('ikysantoso1@gmail.com')->send(new MyTestEmail('dicky'));
});