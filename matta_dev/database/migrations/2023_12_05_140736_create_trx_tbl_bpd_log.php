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
        Schema::create('trx_tbl_bpd_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trx');
            $table->unsignedBigInteger('id_status');
            $table->string('nik_pic');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_bpd_log', function (Blueprint $table) {
            //
            $table->foreign('id_trx')->references('id')->on('trx_tbl_bpd_master');
            $table->foreign('nik_pic')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_status')->references('id')->on('mtr_tbl_bpd_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_bpd_log');
    }
};
