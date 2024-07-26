<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Evaluations\Evaluation;
use App\Models\Evaluations\EvaluationBankQuestion;
use App\Models\Evaluations\EvaluationQuestion;
use Illuminate\Http\Request;

class EvaluationsQuestionsController extends Controller
{

    /**
     * Method to associate questions with an evaluation
     */
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'evaluation_id' => 'required|integer',
            'unrelated_questions' => 'required|array',
            'unrelated_questions.*' => 'integer',
        ]);

        $evaluation_id = $request->input('evaluation_id');
        $questions = $request->input('unrelated_questions');

        $there_is_evaluation = Evaluation::find($evaluation_id);
        if (!$there_is_evaluation) {
            return redirect()->back()->with('error', 'La evaluación no existe.');
        }

        try {
            foreach ($questions as $question_id) {
                // Verificar si la pregunta existe
                $there_is_question = EvaluationBankQuestion::find($question_id);
                if (!$there_is_question) {
                    return redirect()->back()->with('error', 'Una de las preguntas no existe.');
                } else {
                    // Verificar el status de la pregunta en la tabla de relación
                    $status_question = EvaluationQuestion::where('question_id', $question_id)
                        ->where('evaluation_id', $evaluation_id)
                        ->first();

                    if ($status_question && $status_question->status == 0) {
                        // Si la pregunta ya está en la relación pero su estado es 0, actualizar a 1
                        $status_question->update(['status' => 1]);
                    } else {
                        // Si la pregunta no está en la relación, crear una nueva entrada
                        EvaluationQuestion::create([
                            'evaluation_id' => $evaluation_id,
                            'question_id' => $question_id,
                        ]);
                    }
                }
            }

            return redirect()->back()->with('success', 'Preguntas añadidas exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al añadir las preguntas.');
        }
    }


    /**
     * Method to update the status of a question associated with an evaluation
     */
    public function update(Request $request)
    {
        $request->validate([
            'associated_questions' => 'required|array',
            'associated_questions.*' => 'integer',
        ]);

        $question_ids = $request->input('associated_questions');

        try {
            foreach ($question_ids as $question_id) {
                EvaluationQuestion::where('question_id', $question_id)
                    ->update(['status' => 0]);
            }

            return redirect()->back()->with('success', 'Preguntas actualizadas exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar las preguntas.');
        }
    }

}
