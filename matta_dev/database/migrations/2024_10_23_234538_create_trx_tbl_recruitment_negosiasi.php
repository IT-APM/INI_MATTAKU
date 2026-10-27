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
        Schema::create('trx_tbl_recruitment_negosiasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_identitas');
            $table->unsignedBigInteger('id_status');
            $table->string('posisi')->nullable();
            $table->string('area_kerja')->nullable();
            $table->string('divisi')->nullable();
            $table->string('atasan')->nullable();
            $table->text('ket_organisasi')->nullable();
            $table->integer('thp')->nullable();
            $table->string('benefit_utama')->nullable();
            $table->text('benefit_lainnya')->nullable();
            $table->text('ket_benefit')->nullable();
            $table->date('tgl_bergabung')->nullable();
            $table->unsignedBigInteger('id_catat_pegawai')->nullable();
            $table->unsignedBigInteger('id_status_pegawai')->nullable();
            $table->integer('masa_kerja')->nullable();
            $table->text('ket_pegawai')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_recruitment_negosiasi', function (Blueprint $table) {
            //
            $table->foreign('id_identitas')->references('id')->on('trx_tbl_recruitment_identitas');
            $table->foreign('id_status')->references('id')->on('mtr_tbl_recruitment_status');
            $table->foreign('id_catat_pegawai')->references('id')->on('mtr_tbl_recruitment_catat_pegawai');
            $table->foreign('id_status_pegawai')->references('id')->on('mtr_tbl_recruitment_status_pegawai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_recruitment_negosiasi');
    }
};
