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
        Schema::table('mtr_tbl_karyawan', function (Blueprint $table) {
            //
            $table->date('tanggal_bergabung')->after('kode_jabatan')->nullable();
            $table->date('tanggal_berakhir')->after('tanggal_bergabung')->nullable();
            $table->date('tanggal_karyawan_tetap')->after('tanggal_berakhir')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mtr_tbl_karyawan', function (Blueprint $table) {
            //
        });
    }
};
