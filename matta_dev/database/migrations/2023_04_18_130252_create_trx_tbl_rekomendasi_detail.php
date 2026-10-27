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
        Schema::create('trx_tbl_rekomendasi_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trx');
            $table->unsignedBigInteger('id_pengajuan');
            $table->string('sebelum')->nullable();
            $table->string('setelah')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_rekomendasi_detail', function (Blueprint $table) {
            //
            $table->foreign('id_trx')->references('id')->on('trx_tbl_rekomendasi_master');
            $table->foreign('id_pengajuan')->references('id')->on('mtr_tbl_rekomendasi_pengajuan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_rekomendasi_detail');
    }
};
