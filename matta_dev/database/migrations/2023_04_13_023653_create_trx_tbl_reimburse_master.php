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
        Schema::create('trx_tbl_reimburse_master', function (Blueprint $table) {
            $table->id();
            $table->string('nik_karyawan');
            $table->integer('periode');
            $table->double('plafon', 15, 2)->default(0);
            $table->double('plafon_diambil', 15, 2)->default(0);
            $table->double('sisa_plafon_1', 15, 2)->default(0);
            $table->double('klaim_kesehatan', 15, 2)->default(0);
            $table->double('klaim_non_kesehatan', 15, 2)->default(0);
            $table->double('sisa_plafon_2', 15, 2)->default(0);
            $table->unsignedBigInteger('status_pengajuan');
            $table->string('catatan', 500)->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_reimburse_master', function (Blueprint $table) {
            //
            $table->foreign('nik_karyawan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('status_pengajuan')->references('id')->on('mtr_tbl_reimburse_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_reimburse_master');
    }
};
