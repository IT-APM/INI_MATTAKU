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
        Schema::create('trx_tbl_reimburse_lampiran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_reimburse');
            $table->unsignedBigInteger('id_lampiran');
            $table->string('filename');
            $table->string('keterangan', 500)->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_reimburse_lampiran', function (Blueprint $table) {
            //
            $table->foreign('id_reimburse')->references('id')->on('trx_tbl_reimburse_master');
            $table->foreign('id_lampiran')->references('id')->on('mtr_tbl_reimburse_lampiran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_reimburse_lampiran');
    }
};
