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
        Schema::create('mtr_tbl_pekerjaan_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pekerjaan');
            $table->unsignedBigInteger('id_kategori');
            $table->string('informasi');
            $table->string('keterangan', 500)->nullable();
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_pekerjaan_detail', function (Blueprint $table) {
            //
            $table->foreign('id_pekerjaan')->references('id')->on('mtr_tbl_pekerjaan_sub');
            $table->foreign('id_kategori')->references('id')->on('mtr_tbl_pekerjaan_kategori');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_pekerjaan_detail');
    }
};
