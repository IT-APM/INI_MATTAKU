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
        Schema::create('mtr_tbl_divisi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('kode_perusahaan');
            $table->timestamps();
        });

        Schema::table('mtr_tbl_divisi', function (Blueprint $table) {
            //
            $table->foreign('kode_perusahaan')->references('id')->on('mtr_tbl_perusahaan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_divisi');
    }
};
