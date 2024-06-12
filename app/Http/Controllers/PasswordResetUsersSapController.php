<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetRequest;
use App\Mail\ResetPasswordMailable;
use App\Models\PasswordResetsUsersSap;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PasswordResetUsersSapController extends Controller
{
    /**
     * I check the user's requests to reset password and show them in their respective view.
     */
    public function index()
    {
        $userId = Auth::id();
        $password_list_reset = PasswordResetsUsersSap::where('user_id', $userId)->get();
        return view('admin.solicitud.index',compact('password_list_reset'));
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
    public function update(Request $request, $id)
    {
        
        $validatedData = $request->validate([
            'id' => 'required|integer'
        ]);
    
        $list_reset = PasswordResetsUsersSap::findOrFail($id);
        
        if (!$list_reset) {
            return response()->json(['message' => 'Ha ocurrido un error.'], 404);
        }

        $password_tmp = $list_reset->password_tmp;
        $tipo_solicitud = $list_reset->tipo_solicitud;


        $usuario = User::find($list_reset->user_id);
        $email = $usuario->email;
        $name_user = $usuario->name;

        try {
            $data = [
                'name_user' => $name_user,
                'tipo_solicitud' => $tipo_solicitud,
                'password_tmp' => $password_tmp
            ];
            Mail::to($email)->send(new ResetPasswordMailable($data));
            
            $list_reset->status = 1;
            $list_reset->save();
            // Redirect with a message
            return response()->json(['message' => 'Solicitud realizada con éxito, recibirás un correo para restablecer tu contraseña'], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'succes' => 'error',
                'message' =>'Ha ocurrido un error,Vulve a intentarlo'.$e->getMessage(),
            ], 500);

        }
    }
}
