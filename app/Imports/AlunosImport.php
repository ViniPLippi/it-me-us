<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;

class AlunosImport implements ToModel
{
    use Importable;

    /**
     * @param array $row
     *
     * @return Products|null
     */

    public function model(array $row)
    {

        return $row;

//        return json_encode($row);


    }
}
?>
