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
        Schema::create('trx_tbl_bpd_detail_biaya', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trx');
            $table->string('jenis')->nullable();
            $table->unsignedBigInteger('id_info')->nullable();
            $table->unsignedBigInteger('id_coa')->nullable();
            $table->string('kode_coa')->nullable();
            $table->string('biaya');
            $table->string('satuan');
            $table->integer('jumlah')->nullable();
            $table->double('nominal', 15, 2)->default(0);
            $table->string('lampiran')->nullable();
            $table->boolean('is_ca')->nullable();
            $table->boolean('is_approve')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_bpd_detail_biaya', function (Blueprint $table) {
            //
            $table->foreign('id_trx')->references('id')->on('trx_tbl_bpd_master');
            $table->foreign('id_info')->references('id')->on('trx_tbl_bpd_detail_info');
            $table->foreign('id_coa')->references('id')->on('mtr_tbl_bpd_coa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_bpd_detail_biaya');
    }
};
