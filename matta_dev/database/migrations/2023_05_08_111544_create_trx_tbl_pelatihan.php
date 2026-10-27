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
        Schema::create('trx_tbl_pelatihan', function (Blueprint $table) {
            $table->id();
            $table->string('pengaju');
            $table->string('atasan_pengaju');
            $table->string('peserta');
            $table->string('atasan_peserta');
            $table->string('reviewer1')->nullable();
            $table->string('reviewer2')->nullable();
            $table->string('pengaju_property', 500)->nullable();
            $table->string('atasan_pengaju_property', 500)->nullable();
            $table->text('peserta_property')->nullable();
            $table->text('atasan_peserta_property')->nullable();
            $table->string('reviewer1_property', 500)->nullable();
            $table->string('reviewer2_property', 500)->nullable();
            $table->integer('ke');
            $table->integer('tahun');
            $table->string('jenis');
            $table->string('judul');
            $table->string('penyelenggara');
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->string('tempat', 1000);
            $table->integer('biaya');
            $table->string('benefit');
            $table->unsignedBigInteger('anggaran');
            $table->string('keterangan', 1000)->nullable();
            $table->unsignedBigInteger('status_pengajuan');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_pelatihan', function (Blueprint $table) {
            //
            $table->foreign('pengaju')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('atasan_pengaju')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('anggaran')->references('id')->on('mtr_tbl_pelatihan_anggaran');
            $table->foreign('status_pengajuan')->references('id')->on('mtr_tbl_pelatihan_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_pelatihan');
    }
};
