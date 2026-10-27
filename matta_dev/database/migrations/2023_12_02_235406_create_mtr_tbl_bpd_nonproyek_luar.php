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
        Schema::create('mtr_tbl_bpd_nonproyek_luar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_negara');
            $table->unsignedBigInteger('id_biaya');
            $table->double('kelompok_1', 15, 2)->default(0);
            $table->double('kelompok_2', 15, 2)->default(0);
            $table->double('kelompok_3', 15, 2)->default(0);
            $table->double('kelompok_4', 15, 2)->default(0);
            $table->double('kelompok_5', 15, 2)->default(0);
            $table->double('kelompok_6', 15, 2)->default(0);
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_bpd_nonproyek_luar', function (Blueprint $table) {
            //
            $table->foreign('id_negara')->references('id')->on('mtr_tbl_negara');
            $table->foreign('id_biaya')->references('id')->on('mtr_tbl_bpd_biaya');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_bpd_nonproyek_luar');
    }
};
