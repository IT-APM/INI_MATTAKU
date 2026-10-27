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
        Schema::create('trx_tbl_kebutuhan_master_edit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kebutuhan');
            $table->string('kolom');
            $table->string('sebelum')->nullable();
            $table->string('sesudah')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('pic');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_kebutuhan_master_edit', function (Blueprint $table) {
            $table->foreign('id_kebutuhan')->references('id')->on('trx_tbl_kebutuhan_master');
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
        Schema::dropIfExists('trx_tbl_kebutuhan_master_edit');
    }
};
