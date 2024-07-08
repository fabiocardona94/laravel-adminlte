<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Cargue;

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
            'action' => 'required|in:replace,append',
        ]);

        $file = $request->file('spreadsheet');

        try {
            // Lógica para procesar el archivo subido

            // Si se selecciona "Reemplazar Datos"
            if ($request->action === 'replace') {
                Cargue::truncate();
            }

            // Cargar y procesar el archivo Excel
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();

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
                    'lote' => $rowData[5] ?? '',
                    'unidad_de_medida' => $rowData[6] ?? '',
                    'libre_utilizacion' => $rowData[7] ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Insertar los datos en la base de datos
            Cargue::insert($insertData);

            // Mensaje de éxito basado en la acción
            $message = ($request->action === 'replace') ? 'Archivo Excel reemplazado exitosamente.' : 'Datos del archivo Excel anexados exitosamente.';

            // Devolver una respuesta JSON con el tamaño total del archivo
            return response()->json([
                'status' => 'success',
                'message' => $message,
               
            ]);

        } catch (\Exception $e) {
            // Manejar errores y devolver una respuesta de error
            return response()->json([
                'status' => 'error',
                'message' => 'Hubo un problema al procesar el archivo.',
            ]);
        }
    }
}
