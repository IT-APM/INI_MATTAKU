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
        Schema::create('trx_tbl_ijin', function (Blueprint $table) {
            $table->id();
            $table->string('karyawan');
            $table->string('karyawan_property', 500)->nullable();
            $table->string('atasan');
            $table->string('atasan_property', 500)->nullable();
            $table->unsignedBigInteger('tipe');
            $table->date('tanggal');
            $table->time('jam_awal')->nullable();
            $table->time('jam_akhir')->nullable();
            $table->string('alasan', 500)->nullable();
            $table->unsignedBigInteger('status');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_ijin', function (Blueprint $table) {
            //
            $table->foreign('karyawan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('atasan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('tipe')->references('id')->on('mtr_tbl_ijin_tipe');
            $table->foreign('status')->references('id')->on('mtr_tbl_ijin_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_ijin');
    }
};
