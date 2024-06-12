<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;


Route::prefix('/admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.index');
    })->name('index');
});
Route::get('/admin.uploadFiles', [AdminController::class, 'vistaArchivos'])->name('cargarArchivo');

Route::post('/upload', [AdminController::class, 'upload'])->name('upload.file');


