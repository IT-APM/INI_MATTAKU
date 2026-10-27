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
        Schema::create('trx_tbl_cuti', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipe_cuti');
            $table->integer('periode_cuti');
            $table->string('nik_karyawan');
            $table->string('nik_atasan');
            $table->string('pic_pengganti');
            $table->string('karyawan_property', 500)->nullable();
            $table->string('atasan_property', 500)->nullable();
            $table->text('pic_property')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->integer('jumlah_hari');
            $table->string('tanggal_cuti', 1000)->nullable();
            $table->string('alasan', 500)->nullable();
            $table->string('alamat', 500)->nullable();
            $table->string('telepon')->nullable();
            $table->string('keterangan', 500)->nullable();
            $table->unsignedBigInteger('status_cuti');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_cuti', function (Blueprint $table) {
            //
            $table->foreign('nik_karyawan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('nik_atasan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('tipe_cuti')->references('id')->on('mtr_tbl_cuti_tipe');
            $table->foreign('status_cuti')->references('id')->on('mtr_tbl_cuti_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_cuti');
    }
};
