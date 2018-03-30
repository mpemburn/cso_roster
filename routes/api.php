<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('member/verify/{email}/{zip}', 'ApiController@getUserFromEmailAndZip');

Route::get('member/list', 'ApiController@getMemberList');

Route::match(['POST', 'GET'], 'member/join', 'ApiController@newMemberJoin');

Route::match(['POST', 'GET'], 'member/payment', 'ApiController@saveDuesPaymentForMember');