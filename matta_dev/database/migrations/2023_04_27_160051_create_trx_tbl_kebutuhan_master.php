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
        Schema::create('trx_tbl_kebutuhan_master', function (Blueprint $table) {
            $table->id();
            $table->string('pengaju');
            $table->string('layer1');
            $table->string('layer2')->nullable();
            $table->string('layer3')->nullable();
            $table->string('layer4')->nullable();
            $table->string('layer5')->nullable();
            $table->string('pengaju_property', 500)->nullable();
            $table->string('layer1_property', 500)->nullable();
            $table->string('layer2_property', 500)->nullable();
            $table->string('layer3_property', 500)->nullable();
            $table->string('layer4_property', 500)->nullable();
            $table->string('layer5_property', 500)->nullable();
            $table->string('catatan_layer1', 500)->nullable();
            $table->string('catatan_layer2', 500)->nullable();
            $table->string('catatan_layer3', 500)->nullable();
            $table->string('catatan_layer4', 500)->nullable();
            $table->string('catatan_layer5', 500)->nullable();
            $table->unsignedBigInteger('id_pekerjaan');
            $table->unsignedBigInteger('id_level');
            $table->unsignedBigInteger('id_status_kerja');
            $table->integer('waktu_kontrak')->nullable();
            $table->integer('jumlah');
            $table->unsignedBigInteger('id_kelamin');
            $table->unsignedBigInteger('id_area')->nullable();
            $table->unsignedBigInteger('id_kota')->nullable();
            $table->unsignedBigInteger('id_jenis_permintaan');
            $table->string('penjelasan', 500)->nullable();
            $table->tinyInteger('id_rencana');
            $table->unsignedBigInteger('id_jenjang_pendidikan');
            $table->unsignedBigInteger('id_jurusan_pendidikan')->nullable();
            $table->double('nilai_ipk', 15, 2)->nullable();
            $table->integer('umur_minimum');
            $table->integer('umur_maksimum');
            $table->unsignedBigInteger('id_pengalaman');
            $table->string('id_pelatihan', 500)->nullable();
            $table->string('id_sertifikasi', 500)->nullable();
            $table->string('id_keterampilan', 500)->nullable();
            $table->string('id_kepribadian', 500)->nullable();
            $table->date('tanggal');
            $table->string('keterangan_lain', 500)->nullable();
            $table->unsignedBigInteger('id_penambahan');
            $table->unsignedBigInteger('status_pengajuan');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_kebutuhan_master', function (Blueprint $table) {
            //
            $table->foreign('pengaju')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('layer1')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_pekerjaan')->references('id')->on('mtr_tbl_pekerjaan_sub');
            $table->foreign('id_level')->references('id')->on('mtr_tbl_level');
            $table->foreign('id_status_kerja')->references('id')->on('mtr_tbl_status_kerja');
            $table->foreign('id_kelamin')->references('id')->on('mtr_tbl_kelamin');
            $table->foreign('id_area')->references('id')->on('mtr_tbl_propinsi');
            $table->foreign('id_kota')->references('id')->on('mtr_tbl_propinsi_kota');
            $table->foreign('id_jenis_permintaan')->references('id')->on('mtr_tbl_jenis_permintaan');
            $table->foreign('id_jenjang_pendidikan')->references('id')->on('mtr_tbl_pendidikan_jenjang');
            $table->foreign('id_jurusan_pendidikan')->references('id')->on('mtr_tbl_pendidikan_jurusan');
            $table->foreign('id_pengalaman')->references('id')->on('mtr_tbl_pengalaman');
            $table->foreign('id_penambahan')->references('id')->on('mtr_tbl_rencana_penambahan');
            $table->foreign('status_pengajuan')->references('id')->on('mtr_tbl_kebutuhan_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_kebutuhan_master');
    }
};
