<?php

use App\Http\Controllers\CargueController;
use Illuminate\Support\Facades\Route;


Route::prefix('/admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.index');
    })->name('index');
});
Route::get('/admin.cargue.uploadFiles', [CargueController::class, 'vistaArchivos'])->name('cargarArchivo');

Route::post('/upload', [CargueController::class, 'upload'])->name('upload.file');


