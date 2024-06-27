<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetRequest;
use App\Mail\ResetPasswordMailable;
use App\Models\PasswordResetsUsersSap;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class PasswordResetUsersSapController extends Controller
{
    /**
     *Show views of password reset requests
     */
    public function index()
    {
        return view('admin.solicitud.index');
    }
    
    /**
     *Method to obtain the list of requests to reset the password
     */
    public function datatable()
    {
        $password_list_reset = PasswordResetsUsersSap::with('user:id,name');
        return DataTables::of($password_list_reset)
            ->addColumn('user_name', function($row){
                return $row->user->name;
            })
            ->addColumn('status', function($row){
                return $row->status == 1 
                    ? '<button type="button" class="btn btn-sm btn-primary">Finalizado</button>'
                    : '<button type="button" class="btn btn-sm btn-danger">Pendiente</button>';
            })
            ->addColumn('actions', function($row){
                return $row->status == 1
                    ? '<button type="button" class="btn btn-sm btn-outline-primary disabled">Email Enviado</button>'
                    : '<button type="button" class="btn btn-sm btn-outline-success" onclick="ResetPassword(\'' . $row->id . '\')">Enviar Email</button>';
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    
    
    }

    /**
     * Method to generate a random temporary password
     */
    private function generateRandomPassword($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Method to save the password reset request
     */
    public function store(PasswordResetRequest $request)
    {
        //Get the user id
        $userId = Auth::id();
        //I store the random password
        $temporaryPassword = $this->generateRandomPassword();

        //I validate the data received
        $validatedData = $request->validated();

        //I add the user id and temporary password
        $validatedData['user_id'] = $userId;
        $validatedData['password_tmp'] = $temporaryPassword;

        //I create a new record in the database
        PasswordResetsUsersSap::create($validatedData);

        return to_route('admin.solicitar.index')->with('status','  La solicitud de restablecimiento de contraseña ha sido exitosa');

    }

    /**
     * Method to update the status and send the email to the user to reset
     * the password and additionally send the temporarily generated password.
     */
    public function update(Request $request)
    {

        
        $request->validate([
            'id' => ['required','integer']
        ]);
    
        $list_reset = PasswordResetsUsersSap::with('user')->findOrFail($request->id);


        if (!$list_reset) {
            return response()->json(['status' => 'error', 'message' => 'Ha ocurrido un error.'], 404);
        }

        $usuario = $list_reset->user;
        $email = $usuario->email;
        $name_user = $usuario->name;
        $password_tmp = $list_reset->password_tmp;
        $tipo_solicitud = $list_reset->tipo_solicitud;

        try {
            $data = [
                'name_user' => $name_user,
                'tipo_solicitud' => $tipo_solicitud,
                'password_tmp' => $password_tmp
            ];

            $subject = "Restablecimiento de la  contraseña de la cuenta de prueba: {$name_user}";
            Mail::to($email)->send(new ResetPasswordMailable($data, $subject));
            $list_reset->status = 1;
            $list_reset->save();
            // Redirect with a message
            return response()->json([
                'status' => 'suceess',
                'message' => 'Solicitud realizada con éxito, recibirás un correo para restablecer tu contraseña'
            ], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => 'error',
                'message' => 'Ha ocurrido un error,Vuelve a intentarlo' . $e->getMessage(),
            ], 500);

        }
    }
}
