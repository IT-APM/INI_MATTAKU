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
        Schema::create('trx_tbl_sakit', function (Blueprint $table) {
            $table->id();
            $table->integer('periode_sakit');
            $table->string('nik_karyawan');
            $table->string('nik_atasan');
            $table->string('pic_pengganti');
            $table->text('karyawan_property')->nullable();
            $table->text('atasan_property')->nullable();
            $table->text('pic_property')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->integer('jumlah_hari');
            $table->text('tanggal_sakit')->nullable();
            $table->string('alasan', 500)->nullable();
            $table->string('alamat', 500)->nullable();
            $table->string('telepon')->nullable();
            $table->string('keterangan', 500)->nullable();
            $table->string('lampiran')->nullable();
            $table->unsignedBigInteger('status_sakit');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_sakit', function (Blueprint $table) {
            //
            $table->foreign('nik_karyawan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('nik_atasan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('status_sakit')->references('id')->on('mtr_tbl_sakit_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_sakit');
    }
};
