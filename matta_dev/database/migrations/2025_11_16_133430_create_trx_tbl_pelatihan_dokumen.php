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
        Schema::create('trx_tbl_pelatihan_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('file_formulir')->nullable();
            $table->unsignedBigInteger('id_trx');
            $table->string('nik');
            $table->text('nik_property')->nullable();
            $table->integer('tahun')->nullable();
            $table->string('jenis')->nullable();
            $table->string('judul')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->text('tanggal_range')->nullable();
            $table->unsignedBigInteger('anggaran')->nullable();
            $table->double('biaya', 15, 2)->nullable();
            $table->string('benefit')->nullable();
            $table->text('tempat')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('tanggal_ujian')->nullable();
            $table->boolean('is_lulus')->nullable();
            $table->date('tanggal_berlaku')->nullable();
            $table->string('no_sertifikat')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('file_anggota')->nullable();
            $table->string('file_lisensi')->nullable();
            $table->string('file_modul')->nullable();
            $table->string('file_pelatihan')->nullable();
            $table->string('file_keahlian')->nullable();
            $table->timestamps();
        });
        Schema::table('trx_tbl_pelatihan_dokumen', function (Blueprint $table) {
            $table->foreign('nik')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_trx')->references('id')->on('trx_tbl_pelatihan');
            $table->foreign('anggaran')->references('id')->on('mtr_tbl_pelatihan_anggaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_pelatihan_dokumen');
    }
};
