<?php

namespace App\Http\Controllers;

use App\Models\ExcelModel;
use DynamicTableImport as GlobalDynamicTableImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminController extends Controller
{
    public function vistaArchivos(){
        return view('admin.uploadFiles');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'spreadsheet' => 'required|file|mimes:xls,xlsx,csv',
        ]);
    
        $file = $request->file('spreadsheet');
    
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();

        ExcelModel::truncate();
    
        $firstRow = true;
    
        foreach ($sheet->getRowIterator() as $row) {
            // Saltar la primera fila
            if ($firstRow) {
                $firstRow = false;
                continue;
            }
    
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
    
            ExcelModel::create([
                'centro' => $rowData[0],
                'almacen' => $rowData[1],
                'material' => $rowData[2],
                'texto_breve_de_material' => $rowData[3],
                'grupo_de_articulos' => $rowData[4],
                'lote' => $rowData[5],
                'unidad_de_medida' => $rowData[6],
                'libre_utilizacion' => $rowData[7]
            ]);
        }
    
        return redirect()->back()->with('success', 'Archivo Excel subido exitosamente.');
    }
    
    
    

}
