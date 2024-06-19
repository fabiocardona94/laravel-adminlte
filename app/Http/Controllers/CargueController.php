<?php

namespace App\Http\Controllers;

use App\Models\Cargue;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CargueController extends Controller
{
    public function vistaArchivos()
    {
        return view('admin.cargue.uploadFiles');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'spreadsheet' => 'required|file|mimes:xls,xlsx',
            'action' => 'required|in:replace,append', // Validar la opción seleccionada
        ]);

        $file = $request->file('spreadsheet');

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();

        // Si se selecciona "Reemplazar Datos"
        if ($request->action === 'replace') {
            Cargue::truncate();
        }

        $insertData = [];

        foreach ($sheet->getRowIterator() as $row) {
            // Saltar la primera fila
            if ($row->getRowIndex() === 1) {
                continue;
            }
        
            $rowData = [];
            $isEmptyRow = true; // Bandera para verificar si la fila está vacía
        
            foreach ($row->getCellIterator() as $cell) {
                $cellValue = $cell->getValue();
                
                // Limpiar espacios en blanco alrededor de la celda
                $trimmedValue = trim($cellValue);
                
                // Considerar la celda vacía si después de limpiar espacios no tiene contenido
                $isEmptyCell = ($trimmedValue === '');
        
                // Si la celda tiene valor no vacío después de limpiar, la fila no está vacía
                if (!$isEmptyCell) {
                    $isEmptyRow = false;
                }
        
                // Agregar el valor limpiado o una cadena vacía si está vacío
                $rowData[] = !$isEmptyCell ? $trimmedValue : '';
            }
        
            // Si la fila está completamente vacía, detiene la iteración
            if ($isEmptyRow) {
                break;
            }
        
            // Preparar los datos para la inserción, manejar celdas vacías con ''
            $insertData[] = [
                'centro' => $rowData[0] ?? '',
                'almacen' => $rowData[1] ?? '',
                'material' => $rowData[2] ?? '',
                'texto_breve_de_material' => $rowData[3] ?? '',
                'grupo_de_articulos' => $rowData[4] ?? '',
                'lote' => $rowData[5] ?? '', // Dejar vacío si es null o solo espacios
                'unidad_de_medida' => $rowData[6] ?? '',
                'libre_utilizacion' => $rowData[7] ?? '',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        

        // Insertar los datos al principio de la tabla
        Cargue::insert($insertData);

        $message = ($request->action === 'replace') ? 'Archivo Excel reemplazado exitosamente.' : 'Datos del archivo Excel anexados exitosamente.';
        return redirect()->back()->with('success', $message);
    }
}
