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
        Schema::create('trx_tbl_pelatihan_penilaian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trx');
            $table->string('nik');
            $table->string('materi1');
            $table->string('materi2');
            $table->string('tipe');
            $table->text('jawaban')->nullable();
            $table->timestamps();
        });
        Schema::table('trx_tbl_pelatihan_penilaian', function (Blueprint $table) {
            $table->foreign('nik')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_trx')->references('id')->on('trx_tbl_pelatihan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_pelatihan_penilaian');
    }
};
