<?php

use Illuminate\Support\Facades\Route;


Route::prefix('/admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('agrupacionzfip.index');
    })->name('index');
});
