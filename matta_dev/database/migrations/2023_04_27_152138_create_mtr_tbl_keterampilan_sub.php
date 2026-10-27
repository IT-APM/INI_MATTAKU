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
        Schema::create('mtr_tbl_keterampilan_sub', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_keterampilan');
            $table->string('sub_keterampilan');
            $table->string('keterangan', 500)->nullable();
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_keterampilan_sub', function (Blueprint $table) {
            //
            $table->foreign('id_keterampilan')->references('id')->on('mtr_tbl_keterampilan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_keterampilan_sub');
    }
};
