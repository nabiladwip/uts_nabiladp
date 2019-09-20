<?php

use Illuminate\Http\Request;




Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::post('saldo/{id}', 'UserController@saldo');
Route::post('transaksi', 'TransaksiController@transaksi');

Route::middleware(['jwt.verify'])->group(function(){
   Route::get('saldo', 'SaldoController@index');
Route::post('saldoall', 'SaldoController@saldoAuth');
Route::get('user', 'UserController@getAuthenticatedUser');

});


