<?php

use Illuminate\Support\Facades\Route;
use Junges\Kafka\Facades\Kafka;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/payment', function (\App\Http\Requests\PaymentRequest $request) {
    Kafka::publishOn('payments')
        ->withBodyKey('data', json_encode($request->validated()))
        ->send();

    return json_encode($request->validated());
});
