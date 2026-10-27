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
        Schema::create('trx_tbl_nonaktif_auto', function (Blueprint $table) {
            $table->id();
            $table->string('nik_karyawan');
            $table->string('nik_atasan');
            $table->unsignedBigInteger('id_status')->nullable();
            $table->unsignedBigInteger('id_trx')->nullable();
            $table->boolean('is_email')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_nonaktif_auto', function (Blueprint $table) {
            //
            $table->foreign('nik_karyawan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('nik_atasan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_status')->references('id')->on('mtr_tbl_nonaktif_status');
            $table->foreign('id_trx')->references('id')->on('trx_tbl_nonaktif_master');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_nonaktif_auto');
    }
};
