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
        Schema::create('mtr_tbl_jadwal_qats_member', function (Blueprint $table) {
            $table->id();$table->string('nik');
            $table->unsignedBigInteger('id_shift');
            $table->date('tanggal_awal')->nullable();
            $table->date('tanggal_akhir')->nullable();
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_jadwal_qats_member', function (Blueprint $table) {
            //
            $table->foreign('nik')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_shift')->references('id')->on('mtr_tbl_jadwal_qats_member_shift');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_jadwal_qats_member');
    }
};
