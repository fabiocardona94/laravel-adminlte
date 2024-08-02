<?php

use App\Http\Controllers\Evaluations\BanksQuestionsController;
use App\Http\Controllers\Evaluations\EvaluationsController;
use App\Http\Controllers\Evaluations\EvaluationsQuestionsController;
use App\Http\Controllers\Evaluations\OptionsController;
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

        Route::get('/viewquestions',[EvaluationsController::class,'viewQuestionsAssociated'])
            ->name('viewQuestionsAssociated');

        Route::get('/preguntas/{id}',[EvaluationsController::class,'getEvaluationQuestions'])
            ->name('preguntas');

        Route::get('/{id}',[EvaluationsController::class,'edit'])
            ->name('edit');

        Route::post('/store',[EvaluationsController::class,'store'])
               ->name('store');


        Route::patch('/update/{id}',[EvaluationsController::class,'update'])
            ->name('update');

    });

    Route::prefix('pregunta')->name('pregunta.')->group(function () {

        Route::get('/index',[BanksQuestionsController::class,'index'])
            ->name('index');

        Route::get('/questions',[BanksQuestionsController::class,'listQuestions'])
            ->name('listQuestions');

        Route::get('/bancopreguntas/{id}/{page}',[BanksQuestionsController::class,'consultQuestionBank'])
            ->name('bancopreguntas');

        Route::get('/edit/{id}',[BanksQuestionsController::class,'edit'])
            ->name('edit');

        Route::post('/store',[BanksQuestionsController::class,'store'])
            ->name('store');

        Route::post('/createquestionasociated',[BanksQuestionsController::class,'createAssociatedQuestion'])
            ->name('createquestionasociated');

        Route::post('/createmultiplequestionsasociated',[BanksQuestionsController::class,'createMultipleAssociatedQuestions'])
            ->name('createmultiplequestionsasociated');

        Route::patch('/update/{id}',[BanksQuestionsController::class,'update'])
            ->name('update');


    });

    Route::prefix('evaluacion_pregunta')->name('evaluacion_pregunta.')->group(function () {

        Route::post('/store',[EvaluationsQuestionsController::class,'store'])
            ->name('store');

        Route::patch('/update/{evaluation_id}/{question_id}',[EvaluationsQuestionsController::class,'update'])
        ->name('update');
    });

    Route::prefix('opciones')->name('opciones.')->group(function () {

        Route::get('opcionesasociadas/{id}',[OptionsController::class,'optionsOfAQuestion'])
            ->name('opciones.asociadas');
    });
});
