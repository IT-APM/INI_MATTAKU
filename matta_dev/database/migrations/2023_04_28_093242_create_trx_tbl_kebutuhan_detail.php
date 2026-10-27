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
        Schema::create('trx_tbl_kebutuhan_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kebutuhan');
            $table->string('nama_karyawan')->nullable();    //mtr_tbl_karyawan
            $table->date('tanggal_interview')->nullable();
            $table->string('file_interview')->nullable();
            $table->date('tanggal_psikotes')->nullable();
            $table->string('file_psikotes')->nullable();
            $table->date('tanggal_negosiasi')->nullable();
            $table->string('alamat', 500)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->unsignedBigInteger('id_pendidikan')->nullable();    //mtr_tbl_pendidikan_jenjang
            $table->string('file_cv')->nullable();
            $table->string('file_sertifikat_keahlian')->nullable();
            $table->string('file_ktp')->nullable();
            $table->string('file_kk')->nullable();
            $table->string('file_npwp')->nullable();
            $table->string('file_bpjs_tk')->nullable();
            $table->string('file_bpjs_kes')->nullable();
            $table->string('file_buku_tabungan')->nullable();
            $table->string('keterangan1', 500)->nullable();
            $table->unsignedBigInteger('id_posisi')->nullable();    //mtr_tbl_pekerjaan_sub
            $table->unsignedBigInteger('id_divisi')->nullable();    //mtr_tbl_sub_departemen
            $table->string('atasan')->nullable();   //mtr_tbl_karyawan
            $table->date('tanggal_peraturan_perusahaan')->nullable();
            $table->date('tanggal_pedoman_prilaku')->nullable();
            $table->string('file_dokumen_ttd')->nullable();
            $table->date('tanggal_struktur_organisasi')->nullable();
            $table->string('keterangan2', 500)->nullable();
            $table->integer('take_home_pay')->nullable();
            $table->string('id_benefit', 500)->nullable();  //mtr_tbl_benefit
            $table->string('keterangan3', 500)->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->unsignedBigInteger('id_pencatatan_kepegawaian')->nullable();    //mtr_tbl_rencana_penambahan
            $table->unsignedBigInteger('id_status_kepegawaian')->nullable();    //mtr_tbl_status_kerja
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->date('tanggal_ttd_kontrak')->nullable();
            $table->string('file_ket_sehat')->nullable();
            $table->string('file_skck')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_kebutuhan_detail', function (Blueprint $table) {
            //
            $table->foreign('id_kebutuhan')->references('id')->on('trx_tbl_kebutuhan_master');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_kebutuhan_detail');
    }
};
