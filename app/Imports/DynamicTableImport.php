<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DynamicTableImport
{
    protected $tableName;
    protected $columns;

    public function __construct($tableName)
    {
        $this->tableName = $tableName;
    }

    public function import(Collection $rows)
    {
        $firstRow = $rows->first(); // Obtiene la primera fila para obtener los nombres de las columnas
        $this->columns = $firstRow->keys()->all(); // Obtiene los nombres de las columnas

        foreach ($rows as $row) {
            $data = [];
            foreach ($this->columns as $column) {
                $data[$column] = $row[$column]; // Asigna el valor de cada columna en la fila
            }
            DB::table($this->tableName)->insert($data);
        }
    }
}

