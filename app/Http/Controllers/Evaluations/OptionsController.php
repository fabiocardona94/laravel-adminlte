<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Evaluations\EvaluationBankQuestion;
use Illuminate\Http\Request;

class OptionsController extends Controller
{

    /**
     *Metho to get the options asocciated an question
     */
    function optionsOfAQuestion($id)
    {
        $questions = EvaluationBankQuestion::with('options')
        ->find($id);

        if (!$questions) {
            return response()->json([
                'status' => 'error',
                'message' => 'Evaluación no encontrada'
            ], 404);
        }

        // Asegúrate de extraer solo las opciones
        $options = $questions->options->map(function($option) {
            return [
                'question_option' => $option->question_option,
                'is_correct' => $option->is_correct
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $options
        ], 200);

    }

}
