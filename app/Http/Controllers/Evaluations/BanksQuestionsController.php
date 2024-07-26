<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Evaluations\EvaluationBankQuestion;
use App\Models\Evaluations\EvaluationQuestion;
use App\Models\Evaluations\EvaluationQuestionOption;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
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

            $question = EvaluationBankQuestion::create([
                'question_title' => $validatedData['question_title'],
            ]);

            // Crear las opciones asociadas a la pregunta
            foreach ($validatedData['options'] as $optionTitle) {
                EvaluationQuestionOption::create([
                    'question_id' => $question->id,
                    'question_option' => $optionTitle,
                ]);
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

    /**
     *Method to create a question associated with an evaluation.
     */
    public function createAssociatedQuestion(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'question_title' => 'required|string|max:255',
            'evaluation_id' => 'required|string|integer',
            'options' => 'required|array',
            'options.*' => 'required|string|max:255',
        ]);

        try {
            // Crear la pregunta
            $question = EvaluationBankQuestion::create([
                'question_title' => $validatedData['question_title'],
            ]);

            // Crear las opciones asociadas a la pregunta
            foreach ($validatedData['options'] as $optionTitle) {
                EvaluationQuestionOption::create([
                    'question_id' => $question->id,
                    'question_option' => $optionTitle,
                ]);

            }

            //Insertar los datos a la tabla realional entre las evaluaciones y preguntas
            EvaluationQuestion::create([
                'evaluation_id' => $validatedData['evaluation_id'],
                'question_id' => $question->id,
            ]);



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


    /**
     * Method for get the data of the evaluations and view end datatable
     */
    public function listQuestions()
    {
        $evaluations = EvaluationBankQuestion::select('id', 'question_title', 'status')->get();
        return DataTables::of($evaluations)
        ->addColumn('status', function($row){
            return $row->status == 1
                ? '<span class="badge badge-success">Activa</span>'
                : '<span class="badge badge-danger">Inactiva</span>';
        })
        ->addColumn('actions', function($row){
            return
                '
                    <div class="d-flex">
                        <button class="btn" type="button" data-toggle="modal" data-target="#editQuestion" data-id="' . $row->id . '"
                            onclick="openEditEvaluationModal(' . $row->id . ')" title="Editar '.$row->title.'">
                            <i class="far fa-edit" style="color: #1655c0;"></i>
                        </button>
                    </div>
                ';
        })
        ->rawColumns(['status', 'actions'])
        ->make(true);
    }
}
