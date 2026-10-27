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
        Schema::create('trx_tbl_recruitment_tiu5', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_identitas');
            $table->integer('jenis');
            $table->integer('nomor');
            $table->integer('urutan');
            $table->string('soal');
            $table->string('jawaban')->nullable();
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->integer('remain')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_recruitment_tiu5', function (Blueprint $table) {
            //
            $table->foreign('id_identitas')->references('id')->on('trx_tbl_recruitment_identitas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_recruitment_tiu5');
    }
};
