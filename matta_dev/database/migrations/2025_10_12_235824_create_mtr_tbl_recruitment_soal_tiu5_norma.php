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
        Schema::create('mtr_tbl_recruitment_soal_tiu5_norma', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->integer('ws');
            $table->integer('bawah')->nullable();
            $table->integer('atas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_recruitment_soal_tiu5_norma');
    }
};
