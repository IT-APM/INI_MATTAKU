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
        Schema::create('mtr_tbl_kebutuhan_nik_kecuali', function (Blueprint $table) {
            $table->id();
            $table->string('karyawan');
            $table->string('layer1');
            $table->string('layer2');
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_kebutuhan_nik_kecuali', function (Blueprint $table) {
            $table->foreign('karyawan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('layer1')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('layer2')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_kebutuhan_nik_kecuali');
    }
};
