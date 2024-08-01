<?php

namespace App\Http\Controllers\Evaluations;

use App\Http\Controllers\Controller;
use App\Models\Evaluations\Evaluation;
use App\Models\Evaluations\EvaluationBankQuestion;
use Illuminate\Http\Request;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;

class EvaluationsController extends Controller
{
    /**
     * Method to show the view of the evaluations that are in the system
     */
    public function index ()
    {
        return view('admin.evaluations.create_evaluation');
    }



    /**
     * Method for creating a new evaluation
     */
    public function store (Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        try {

            Evaluation::create($validatedData);
            return response()->json([
                'status' => 'success',
                'message' => 'Evaluación creada exitosamente'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error',
                'message' => 'Ha ocurrido un error,Vuelve a intentarlo' . $e->getMessage(),
            ], 500);

        }
    }


    /**
     * Method to display the data of an evaluation in the modal and be able to edit said data
     */
    public function edit($id)
    {
        $evaluation = Evaluation::find($id);

        if (!$evaluation) {
            return response()->json([
                'status' => 'error',
                'message' => 'Evaluación no encontrada'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $evaluation
        ], 200);
    }

    /**
     * Method to update the evaluation
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:300',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|integer',
        ]);

        try {
            $evaluation = Evaluation::findOrFail($id);
            $evaluation->title = $validatedData['title'];
            $evaluation->description = $validatedData['description'];
            $evaluation->start_date = $validatedData['start_date'];
            $evaluation->end_date = $validatedData['end_date'];
            $evaluation->status = $validatedData['status'];
            $evaluation->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Evaluación actualizada exitosamente',
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
    public function evaluations()
    {
        $evaluations = Evaluation::select('id', 'title', 'description', 'start_date', 'end_date', 'status')->get();
        return DataTables::of($evaluations)
        ->addColumn('status', function($row){
            return $row->status == 1
                ? '<span class="badge badge-success">Activa</span>'
                : '<span class="badge badge-danger">Inactiva</span>';
        })
        ->addColumn('actions', function($row){
            $rutaPreguntas = route('admin.evaluacion.preguntas', ['id' => $row->id]);
            return   <<<EOF
                <div class="d-flex">
                    <button class="btn" type="button" dsata-toggle="modal" data-target="#editEvalution" data-id="{$row->id}"
                        onclick="openEditEvaluationModal({$row->id})" title="Editar $row->title">
                        <i class="far fa-edit" style="color: #1655c0;"></i>
                    </button>
                    <a href="{$rutaPreguntas}" class="btn" type="button" title="Agregar Preguntas para la {$row->title}">
                        <i class="fas fa-plus" style="color: #0a53d1;"></i>
                    </a>
                </div>
            EOF;
        })
        ->rawColumns(['status', 'actions'])
        ->make(true);

    }

    /**
     * Mehod for get the Questions asosiations and not asosiations of the evaluation
     */
    public function getEvaluationQuestions($id)
    {
        $evaluation = Evaluation::select('id', 'title', 'description')
                                ->with('activeQuestions')
                                ->find($id);

        if(!$evaluation){
            return redirect()->route('admin.evaluacion.index')->with('error', 'Evaluación no encontrada');
        }

        $associated_questions  = $evaluation->activeQuestions;
        $quantity_associated_questions = $associated_questions->count();

        return view('admin.evaluations.view_associated_questions',compact('evaluation','associated_questions','quantity_associated_questions'));
    }


}
