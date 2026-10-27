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
        Schema::create('trx_tbl_penilaian_log_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trx');
            $table->unsignedBigInteger('id_status');
            $table->string('nik_pic');
            $table->string('keterangan', 500)->nullable();
            $table->boolean('is_perpanjang')->nullable();
            $table->integer('bulan')->nullable();
            $table->string('revised_by')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_penilaian_log_history', function (Blueprint $table) {
            //
            $table->foreign('nik_pic')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('revised_by')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_trx')->references('id')->on('trx_tbl_penilaian');
            $table->foreign('id_status')->references('id')->on('mtr_tbl_penilaian_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_penilaian_log_history');
    }
};
