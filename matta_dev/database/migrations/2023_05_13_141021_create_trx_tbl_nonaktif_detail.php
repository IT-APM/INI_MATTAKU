<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trx_tbl_nonaktif_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trx');
            $table->string('_into_');
            $table->string('kepada');
            $table->string('kepada_property', 500)->nullable();
            $table->unsignedBigInteger('obyek');
            $table->string('keterangan', 500)->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_nonaktif_detail', function (Blueprint $table) {
            //
            $table->foreign('id_trx')->references('id')->on('trx_tbl_nonaktif_master');
            $table->foreign('kepada')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('obyek')->references('id')->on('mtr_tbl_nonaktif_obyek');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_nonaktif_detail');
    }
};
