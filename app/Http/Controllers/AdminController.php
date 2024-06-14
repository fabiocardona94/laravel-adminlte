<?php

namespace App\Http\Controllers;

use App\Models\ExcelModel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AdminController extends Controller
{
    public function vistaArchivos()
    {
        return view('admin.uploadFiles');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'spreadsheet' => 'required|file|mimes:xls,xlsx,csv',
            'action' => 'required|in:replace,append', // Validar la opción seleccionada
        ]);

        $file = $request->file('spreadsheet');

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();

        // Si se selecciona "Reemplazar Datos"
        if ($request->action === 'replace') {
            ExcelModel::truncate();
        }

        $insertData = [];

        foreach ($sheet->getRowIterator() as $row) {

            // Saltar la primera fila 
            if ($row->getRowIndex() === 1) {
                continue;
            }

            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {

                if ($cell) {
                    
                }

                $rowData[] = $cell->getValue();

            }

            // Preparar los datos para la inserción
            $insertData[] = [
                'centro' => $rowData[0],
                'almacen' => $rowData[1],
                'material' => $rowData[2],
                'texto_breve_de_material' => $rowData[3],
                'grupo_de_articulos' => $rowData[4],
                'lote' => $rowData[5],
                'unidad_de_medida' => $rowData[6],
                'libre_utilizacion' => $rowData[7],
                'created_at' => now(),
                'updated_at' => now(), 
            ];
        }

        // Insertar los datos al principio de la tabla
        ExcelModel::insert($insertData);

        $message = ($request->action === 'replace') ? 'Archivo Excel reemplazado exitosamente.' : 'Datos del archivo Excel anexados exitosamente.';
        return redirect()->back()->with('success', $message);
    }
}
