<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriarTabelaEscolas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escolas', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->id()->autoIncrement();
            $table->char("esc_inep",8);
            $table->string("esc_razao",100)->nullable($value = true);
            $table->tinyInteger("esc_localizacao")->nullable($value = true);
            $table->string("esc_logradouro",150)->nullable($value = true);
            $table->bigInteger("esc_cep_id");
            $table->string("esc_telefone",15)->nullable($value = true);
            $table->tinyInteger("esc_restricao")->nullable($value = true);
            $table->tinyInteger("esc_local_dif")->nullable($value = true);
            $table->tinyInteger("esc_cat_adm")->nullable($value = true);
            $table->tinyInteger("esc_dep_adm")->nullable($value = true);
            $table->tinyInteger("esc_cat_esc_priv")->nullable($value = true);
            $table->tinyInteger("esc_conv_pod_pub")->nullable($value = true);
            $table->tinyInteger("esc_reg_cons_edu")->nullable($value = true);
            $table->string("esc_porte",50)->nullable($value = true);
            $table->string("esc_eta_mod_ens_ofe",120)->nullable($value = true);
            $table->string("esc_out_ofe_ens",100)->nullable($value = true);
            $table->double("esc_latitude")->nullable($value = true);
            $table->double("esc_longitude")->nullable($value = true);
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
        Schema::dropIfExists('escola');
    }
}
