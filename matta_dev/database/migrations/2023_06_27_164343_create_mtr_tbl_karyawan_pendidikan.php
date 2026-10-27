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
        Schema::create('mtr_tbl_karyawan_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->unsignedBigInteger('id_jenjang');
            $table->string('nama', 100)->nullable();
            $table->string('jurusan_prodi', 100)->nullable();
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_karyawan_pendidikan', function (Blueprint $table) {
            //
            $table->foreign('nik')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_jenjang')->references('id')->on('mtr_tbl_pendidikan_jenjang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_karyawan_pendidikan');
    }
};
