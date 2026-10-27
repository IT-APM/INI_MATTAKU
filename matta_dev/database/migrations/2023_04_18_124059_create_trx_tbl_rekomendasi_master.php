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
        Schema::create('trx_tbl_rekomendasi_master', function (Blueprint $table) {
            $table->id();
            $table->string('nik_karyawan');
            $table->string('karyawan_property', 500)->nullable();
            $table->string('nik_atasan');
            $table->string('atasan_property', 500)->nullable();
            $table->date('terhitung_tanggal');
            $table->string('dasar_pengajuan', 1000)->nullable();
            $table->string('catatan_pengajuan', 1000)->nullable();
            $table->string('nik_reviewer1');
            $table->string('reviewer1_property', 500)->nullable();
            $table->string('nik_reviewer2')->nullable();
            $table->string('reviewer2_property', 500)->nullable();
            $table->string('nik_reviewer3')->nullable();
            $table->string('reviewer3_property', 500)->nullable();
            $table->string('catatan_reviewer1', 1000)->nullable();
            $table->string('catatan_reviewer2', 1000)->nullable();
            $table->string('catatan_reviewer3', 1000)->nullable();
            $table->unsignedBigInteger('status_rekomendasi');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_rekomendasi_master', function (Blueprint $table) {
            //
            $table->foreign('nik_karyawan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('nik_atasan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('nik_reviewer1')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('status_rekomendasi')->references('id')->on('mtr_tbl_rekomendasi_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_rekomendasi_master');
    }
};
