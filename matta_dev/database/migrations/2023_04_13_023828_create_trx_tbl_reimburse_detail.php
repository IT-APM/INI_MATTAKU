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
        Schema::create('trx_tbl_reimburse_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reimburse');
            $table->string('nama_pasien');
            $table->unsignedBigInteger('id_hubungan');
            $table->unsignedBigInteger('id_layanan');
            $table->date('tanggal');
            $table->double('jumlah_klaim', 15, 2)->default(0);
            $table->string('keterangan', 500)->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_reimburse_detail', function (Blueprint $table) {
            //
            $table->foreign('id_reimburse')->references('id')->on('trx_tbl_reimburse_master');
            $table->foreign('id_hubungan')->references('id')->on('mtr_tbl_reimburse_hubungan');
            $table->foreign('id_layanan')->references('id')->on('mtr_tbl_reimburse_layanan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_reimburse_detail');
    }
};
