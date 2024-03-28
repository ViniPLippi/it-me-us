<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaTurmaHasAplicadorHasTestes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turmahasaplicadorhastestes', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->id()->autoIncrement();
            $table->integer('tat_users_id_aplicador');
            $table->integer('tat_tur_id');
            $table->integer('tat_tst_id');
            $table->tinyInteger('tat_status');
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
