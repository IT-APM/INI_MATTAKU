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
        Schema::create('mtr_tbl_struktur_gaji', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_grade');
            $table->integer('tingkat');
            $table->integer('tahun');
            $table->double('gapok', 15, 2)->default(0);
            $table->double('t_makan', 15, 2)->default(0);
            $table->double('t_transport', 15, 2)->default(0);
            $table->double('t_jabatan', 15, 2)->default(0);
            $table->double('t_telekomunikasi', 15, 2)->default(0);
            $table->double('t_lain', 15, 2)->default(0);
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_struktur_gaji', function (Blueprint $table) {
            //
            $table->foreign('id_grade')->references('id')->on('mtr_tbl_karyawan_grade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_struktur_gaji');
    }
};
