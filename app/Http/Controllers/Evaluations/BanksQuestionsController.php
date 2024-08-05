<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Evaluations\Evaluation;
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
    public function store(Request $request)
    {
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

            //Insertar los datos a la tabla relacional entre las evaluaciones y preguntas
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
     * Method to add multiples  questions associated with an evaluation.
     */
    public function createMultipleAssociatedQuestions(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'evaluation_id' => 'required|string|integer',
            'questions' => 'required|array',
            'questions.*' => 'required|string|max:255',
        ]);

        $evaluation_id = $validatedData['evaluation_id'];

        try {
            // Crear las preguntas asociadas a la pregunta
            foreach ($validatedData['questions'] as $question_id) {
                EvaluationQuestion::create([
                    'evaluation_id' =>$evaluation_id,
                    'question_id' => $question_id,
                ]);

            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pregunta agregada exitosamente'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error, vuelve a intentarlo: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Method to open modal and display question data.
     */
    function edit($id)
    {

        $question = EvaluationBankQuestion::select('id','question_title','status')->find($id);
        $options_asociated = EvaluationBankQuestion::with('options')
        ->find($id);


        if (!$question) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pregunta no encontrada'
            ], 404);
        }

        try {

            return response()->json([
                'status' => 'success',
                'question' => $question,
                'options_asociated' => $options_asociated
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error, vuelve a intentarlo: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Method for edit an question
     */
    function update (Request $request,$id)
    {

        $validatedData = $request->validate([
            'question_title' => 'required|string|max:255',
            'status' => 'required|integer',
        ]);

        try {
            $question = EvaluationBankQuestion::findOrFail($id);
            $question->question_title = $validatedData['question_title'];
            $question->status = $validatedData['status'];
            $question->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Pregunta actualizada exitosamente',
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
                        <button class="btn" type="button" onclick="openModalEditQuestion(' . $row->id . ')" title="Editar '.$row->title.'">
                            <i class="far fa-edit" style="color: #1655c0;"></i>
                        </button>
                    </div>
                ';
        })
        ->rawColumns(['status', 'actions'])
        ->make(true);
    }

    /**
     *Method to obtain questions from the question bank and questions associated with an evaluation
     */
    public function consultQuestionBank($id,$page)
    {
        //Busco las preguntas existentes en la relacion de muchos a muchos en el modelo Evaluation
        $evaluation = Evaluation::select('id', 'title', 'description')
                                ->with('questions')
                                ->find($id);

        //Valido si existe relaciones y si  no envio  un mensaje
        if (!$evaluation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Evaluación no encontrada'
            ], 404);
        }

        //Si existe obtengo las preguntas relacinadas
        try {
            $asociated_questions  = $evaluation->questions;
            $quantity_associated_questions = $asociated_questions->count();

            $associated_question_ids = $evaluation->questions->pluck('id')->toArray();
            $perPage = 10;

            // Busca en el banco de preguntas las preguntas que no están relacionadas
            $unrelated_questions = EvaluationBankQuestion::whereNotIn('id', $associated_question_ids)
                                                        ->select('id', 'question_title')
                                                        ->paginate($perPage, ['*'], 'page', $page);


            return response()->json([
                'status' => 'success',
                'unrelated_questions'  => $unrelated_questions->items(),
                'quantity_associated_questions'  => $quantity_associated_questions,
                'pagination' => [
                    'current_page' => $unrelated_questions->currentPage(),
                    'last_page' => $unrelated_questions->lastPage(),
                    'per_page' => $unrelated_questions->perPage(),
                    'total' => $unrelated_questions->total(),
                ],
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ha ocurrido un error, vuelve a intentarlo: ' . $e->getMessage(),
            ], 500);
        }
    }
}
