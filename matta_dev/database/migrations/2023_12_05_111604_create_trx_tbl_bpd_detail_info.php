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
        Schema::create('trx_tbl_bpd_detail_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trx');
            $table->string('jenis')->nullable();
            $table->string('luar_dalam');
            $table->unsignedBigInteger('tujuan1')->nullable();
            $table->unsignedBigInteger('tujuan2')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->boolean('is_approve')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_bpd_detail_info', function (Blueprint $table) {
            //
            $table->foreign('id_trx')->references('id')->on('trx_tbl_bpd_master');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_bpd_detail_info');
    }
};
