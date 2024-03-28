<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaSecretarias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secretarias', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->id()->autoIncrement();
            $table->string('sec_cnpj', 14)->unique();
            $table->string('sec_razao', 100);
            $table->tinyInteger('sec_tipo');
            $table->string('sec_logradouro', 120);
            $table->tinyInteger('sec_numero');
            $table->string('sec_complemento', 20);
            $table->string('sec_cep', 8);
            $table->integer('sec_cid_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
