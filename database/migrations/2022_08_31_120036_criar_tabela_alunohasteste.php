<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaAlunoHasTeste extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunohastestes', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->id()->autoIncrement();
            $table->integer('aht_tat_id');
            $table->integer('aht_alu_id');
            $table->integer('aht_tst_id');
            $table->integer('aht_tur_id');
            $table->integer('aht_esc_id');
            $table->integer('aht_cid_id');
            $table->integer('aht_est_id');
            $table->integer('aht_users_id_professor');
            $table->integer('aht_users_id_aplicador')->nullable(true);
            $table->tinyInteger('aht_grupoaplic');
            $table->integer('aht_ind11')->nullable(true);
            $table->integer('aht_ind12')->nullable(true);
            $table->integer('aht_ind21')->nullable(true);
            $table->integer('aht_ind22')->nullable(true);
            $table->integer('aht_ind31')->nullable(true);
            $table->integer('aht_ind32')->nullable(true);
            $table->string('aht_arqaudio1', 250)->nullable(true);
            $table->string('aht_arqaudio2', 250)->nullable(true);
            $table->string('aht_arqaudio3', 250)->nullable(true);
            $table->tinyInteger('aht_status');
            $table->tinyInteger('aht_statusarq1');
            $table->tinyInteger('aht_statusarq2');
            $table->tinyInteger('aht_statusarq3');
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
