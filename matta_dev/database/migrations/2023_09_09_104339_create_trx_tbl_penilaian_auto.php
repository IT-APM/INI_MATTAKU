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
        Schema::create('trx_tbl_penilaian_auto', function (Blueprint $table) {
            $table->id();
            $table->string('nik_karyawan');
            $table->string('nik_penilai_1');
            $table->string('nik_penilai_2')->nullable();
            $table->string('nik_penilai_3')->nullable();
            $table->unsignedBigInteger('id_tipe');
            $table->unsignedBigInteger('id_status');
            $table->unsignedBigInteger('id_trx')->nullable();
            $table->boolean('is_email');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_penilaian_auto', function (Blueprint $table) {
            //
            $table->foreign('nik_karyawan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('nik_penilai_1')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_tipe')->references('id')->on('mtr_tbl_penilaian_tipe');
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
        Schema::dropIfExists('trx_tbl_penilaian_auto');
    }
};
