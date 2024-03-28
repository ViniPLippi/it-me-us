<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaTestes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testes', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->id()->autoIncrement();
            $table->string('tst_descricao',50);
            $table->tinyInteger('tst_sec_id');
            $table->tinyInteger('tst_esc_id');
            $table->date('tst_ini');
            $table->date('tst_fim');
            $table->tinyInteger('tst_principal');
            $table->text('tst_texto');
            $table->tinyText('tst_palavras');
            $table->tinyText('tst_pseudopalavras');
            $table->date('tst_data_iniadesao');
            $table->date('tst_data_fimadesao');
            $table->integer('tst_id_treinamento');
            $table->tinyInteger('tst_status');
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
