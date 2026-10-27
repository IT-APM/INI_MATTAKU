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
        Schema::create('trx_tbl_absensi_kesehatan', function (Blueprint $table) {
            $table->id();
            $table->string('karyawan');
            $table->string('karyawan_property', 500)->nullable();
            $table->date('tanggal_input');
            $table->string('ring_ke');
            $table->string('ring_isian');
            $table->string('siapa')->nullable();
            $table->string('sakit')->nullable();
            $table->date('tanggal_sakit')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_absensi_kesehatan', function (Blueprint $table) {
            //
            $table->foreign('karyawan')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_absensi_kesehatan');
    }
};
