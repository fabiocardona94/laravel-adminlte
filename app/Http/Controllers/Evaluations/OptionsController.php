<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Evaluations\EvaluationBankQuestion;
use App\Models\Evaluations\EvaluationQuestion;
use App\Models\Evaluations\EvaluationQuestionOption;
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

    function update($id)
    {
        $option = EvaluationQuestionOption::find($id);
        if($option){
            try {
                $option->is_correct = 0;
                $option->percentage_value = 0;
                $option->status = 0;
                $option->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Opción eliminada de la evaluación correctamente'
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Hubo un error al eliminar la  opción: ' . $e->getMessage()
                ], 500);
            }
        }
    }

}
