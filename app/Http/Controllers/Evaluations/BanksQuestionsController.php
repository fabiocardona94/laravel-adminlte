<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Evaluations\EvaluationBankQuestion;
use App\Models\Evaluations\EvaluationQuestionOption;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BanksQuestionsController extends Controller
{
    
    /**
     *  Method to show the view of the questions that are in the system
     */
    function index () {
        return view('admin.evaluations.create_question');
    }

    /**
     * Method for creating a new question
     */
    public function store(Request $request) {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'question_title' => 'required|string|max:255',
            'options' => 'required|array',
            'options.*' => 'required|string|max:255',
        ]);
    
        try {
            // Crear la pregunta
            $question = new EvaluationBankQuestion();
            $question->question_title = $validatedData['question_title'];
            $question->save();
    
            // Crear las opciones asociadas a la pregunta
            foreach ($validatedData['options'] as $optionTitle) {
                $option = new EvaluationQuestionOption(); 
                $option->question_id = $question->id;
                $option->question_option = $optionTitle;
                $option->save();
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'Pregunta creada exitosamente'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error, vuelve a intentarlo: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function questionList()
    {
        $questions = EvaluationBankQuestion::select('id', 'question_title', 'status')->get();
        return DataTables::of($questions)
        ->addColumn('status', function($row){
            return $row->status == 1 
                ? '<span class="badge badge-success">Activa</span>'
                : '<span class="badge badge-danger">Inactiva</span>';
        })
        ->addColumn('actions', function($row){
            return
                '
                    <div class="d-flex">
                        <button class="btn" type="button"">
                            <i class="far fa-edit" style="color: #1655c0;"></i>
                        </button>
                        <a class="btn" type="button">
                            <i class="far fa-question-circle" style="color: #12f321;"></i>
                        </a>
                    </div>
                ';
        })
        ->rawColumns(['status', 'actions'])
        ->make(true);

    }
}
