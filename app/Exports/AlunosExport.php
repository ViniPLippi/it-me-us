<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class AlunosExport implements FromArray
{
    protected $alunos;

    public function __construct(array $alunos)
    {
        $this->alunos = $alunos;
    }

    public function array(): array
    {
        return $this->alunos;
    }
}

