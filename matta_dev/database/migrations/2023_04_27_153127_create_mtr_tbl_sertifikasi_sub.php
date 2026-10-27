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
        Schema::create('mtr_tbl_sertifikasi_sub', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sertifikasi');
            $table->string('sub_sertifikasi');
            $table->string('keterangan', 500)->nullable();
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_sertifikasi_sub', function (Blueprint $table) {
            //
            $table->foreign('id_sertifikasi')->references('id')->on('mtr_tbl_sertifikasi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_sertifikasi_sub');
    }
};
