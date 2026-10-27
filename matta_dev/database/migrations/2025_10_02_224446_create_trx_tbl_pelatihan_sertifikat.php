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
        Schema::create('trx_tbl_pelatihan_sertifikat', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable();
            $table->string('nama')->nullable();
            $table->string('nik2')->nullable();
            $table->string('status')->nullable();
            $table->string('direktorat')->nullable();
            $table->string('divisi')->nullable();
            $table->string('departemen')->nullable();
            $table->string('sub_departemen')->nullable();
            $table->string('jenis')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('kta')->nullable();
            $table->string('judul')->nullable();
            $table->string('tanggal_mulai')->nullable();
            $table->string('tanggal_akhir')->nullable();
            $table->string('tanggal_sertifikat')->nullable();
            $table->string('nomor_sertifikat')->nullable();
            $table->string('jumlah_hari')->nullable();
            $table->string('lembaga')->nullable();
            $table->double('biaya', 15, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('formulir_link')->nullable();
            $table->string('sertifikat_link')->nullable();
            $table->string('tanggal_kadaluarsa')->nullable();
            $table->text('catatan')->nullable();
            $table->string('formulir_file')->nullable();
            $table->string('sertifikat_file')->nullable();
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
        Schema::dropIfExists('trx_tbl_pelatihan_sertifikat');
    }
};
