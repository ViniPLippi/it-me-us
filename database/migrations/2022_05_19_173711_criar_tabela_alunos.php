<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaAlunos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->charset = 'utf8';
             $table->id()->autoIncrement();
             $table->string('alu_nome',50);
             $table->string('alu_rge',15);
             $table->date('alu_nasc');
             $table->integer('alu_est_id');
             $table->timestamps();
             $table->unique(['alu_rge', 'alu_est_id']);
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
