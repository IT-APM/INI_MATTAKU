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
        Schema::create('trx_tbl_nonaktif_master', function (Blueprint $table) {
            $table->id();
            $table->string('pengaju');
            $table->string('pengaju_property', 500)->nullable();
            $table->string('atasan');
            $table->string('atasan_property', 500)->nullable();
            $table->unsignedBigInteger('alasan');
            $table->date('tanggal');
            $table->string('keterangan', 500)->nullable();
            $table->unsignedBigInteger('status');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_nonaktif_master', function (Blueprint $table) {
            //
            $table->foreign('pengaju')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('atasan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('alasan')->references('id')->on('mtr_tbl_nonaktif_alasan');
            $table->foreign('status')->references('id')->on('mtr_tbl_nonaktif_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_nonaktif_master');
    }
};
