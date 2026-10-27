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
        Schema::create('mtr_tbl_bpd_nik_peran', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('admin');
            $table->unsignedBigInteger('id_peran');
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_bpd_nik_peran', function (Blueprint $table) {
            //
            $table->foreign('nik')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('admin')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_peran')->references('id')->on('mtr_tbl_bpd_peran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_bpd_nik_peran');
    }
};
