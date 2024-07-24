<?php

use App\Http\Controllers\Evaluations\BanksQuestionsController;
use App\Http\Controllers\Evaluations\EvaluationsController;
use App\Http\Controllers\Evaluations\EvaluationsQuestionsController;
use Illuminate\Support\Facades\Route;


Route::prefix('/admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return view('admin.index');
    })->name('index');

    Route::prefix('evaluacion')->name('evaluacion.')->group(function () {

        Route::get('/index',[EvaluationsController::class,'index'])
            ->name('index');

        Route::get('/evaluations',[EvaluationsController::class,'evaluations'])
            ->name('evaluations');
            
        Route::get('/preguntas/{id}',[EvaluationsController::class,'getEvaluationQuestions'])
            ->name('getEvaluationQuestions');


        Route::post('/store',[EvaluationsController::class,'store'])
               ->name('store');

        Route::get('/{id}',[EvaluationsController::class,'edit'])
             ->name('edit');

        Route::patch('/update/{id}',[EvaluationsController::class,'update'])
            ->name('update');
         
    });

    Route::prefix('pregunta')->name('pregunta.')->group(function () {
        
        Route::get('/index',[BanksQuestionsController::class,'index'])
            ->name('index');

            Route::get('/questions',[BanksQuestionsController::class,'questionList'])
            ->name('questionList');
               
        Route::post('/store',[BanksQuestionsController::class,'store'])
            ->name('store');        
    });

    Route::prefix('evaliacion_pregunta')->name('evaliacion_pregunta.')->group(function () {
        
        Route::post('/store',[EvaluationsQuestionsController::class,'store'])
            ->name('store');   
            
        Route::patch('/editar',[EvaluationsQuestionsController::class,'update'])
            ->name('update');       
    });
});
