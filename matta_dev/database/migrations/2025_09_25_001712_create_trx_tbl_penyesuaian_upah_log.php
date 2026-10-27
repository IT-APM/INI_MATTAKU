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
        Schema::create('trx_tbl_penyesuaian_upah_log', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->nullable();
            $table->string('nik');
            $table->text('nik_property')->nullable();
            $table->string('pic');
            $table->text('pic_property')->nullable();
            $table->unsignedBigInteger('id_status');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_penyesuaian_upah_log', function (Blueprint $table) {
            $table->foreign('nik')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('pic')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_penyesuaian_upah_log');
    }
};
