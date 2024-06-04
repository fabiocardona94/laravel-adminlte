<?php

namespace App\Http\Controllers;
use DynamicTableImport as GlobalDynamicTableImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;



class AdminController extends Controller
{
    public function vistaArchivos(){
        return view('admin.uploadFiles');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
    
        $file = $request->file('file');
        $tableName = 'table_' . time();  // Genera un nombre de tabla único
    
        // Importa los datos del archivo Excel a la nueva tabla
        Excel::import(new GlobalDynamicTableImport($tableName), $file);
    
        return response()->json(['success' => 'La tabla fue creada en la base de datos y los datos se importaron correctamente.']);
    }
    

}
