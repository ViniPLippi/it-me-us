<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secretaria extends Model
{
    use HasFactory;

    protected $table = 'secretarias';
    protected $primaryKey = 'id';
    protected $fillable = ['sec_cnpj', 'sec_razao', 'sec_tipo', 'sec_logradouro', 'sec_numero', 'sec_complemento', 'sec_cep', 'sec_cid_id'];
}
