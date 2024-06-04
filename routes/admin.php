<?php

use App\Http\Controllers\PasswordResetUsersSapController;
use App\Mail\ResetPasswordMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::prefix('/admin')->name('admin.')->middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('admin.index');
    })->name('index');


    Route::prefix('solicitar')->name('solicitar.')->group(function () {


        Route::get('/',[PasswordResetUsersSapController::class,'index'])
               ->name('index');
               
        Route::post('/',[PasswordResetUsersSapController::class,'store'])
               ->name('store');
          

        Route::patch('/{list_reset}',[PasswordResetUsersSapController::class,'update'])
        ->name('update');

    
    });
});
