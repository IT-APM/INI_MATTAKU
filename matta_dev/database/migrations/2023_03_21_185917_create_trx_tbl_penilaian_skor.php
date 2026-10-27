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
        Schema::create('trx_tbl_penilaian_skor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trx');
            $table->unsignedBigInteger('id_nilai3');
            $table->string('penilaian_1', 500)->nullable();
            $table->string('penilaian_2', 500)->nullable();
            $table->string('penilaian_2_ket', 500)->nullable();
            $table->string('penilaian_3', 500)->nullable();
            $table->boolean('is_spv')->default(0);
            $table->integer('skor_karyawan')->nullable();
            $table->integer('skor_penilai_1')->nullable();
            $table->integer('skor_penilai_2')->nullable();
            $table->integer('skor_penilai_3')->nullable();
            $table->string('keterangan', 500)->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_penilaian_skor', function (Blueprint $table) {
            //
            $table->foreign('id_trx')->references('id')->on('trx_tbl_penilaian');
            $table->foreign('id_nilai3')->references('id')->on('mtr_tbl_penilaian_3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_penilaian_skor');
    }
};
