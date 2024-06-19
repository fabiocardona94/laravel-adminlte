<?php

use App\Http\Controllers\CargueController;
use Illuminate\Support\Facades\Route;


Route::prefix('/admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.index');
    })->name('index');


    Route::prefix('/cargue')->name('cargue.')->group(function () {
        Route::get('/uploadFiles', [CargueController::class, 'vistaArchivos'])->name('cargarArchivo');
        Route::post('/upload', [CargueController::class, 'upload'])->name('upload.file');
    });
});


