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
        Schema::create('mtr_tbl_bpd_proyek_kelompok_uangsaku', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_proyek');
            $table->unsignedBigInteger('id_kelompok');
            $table->double('uangsaku', 15, 2)->default(0);
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_bpd_proyek_kelompok_uangsaku', function (Blueprint $table) {
            //
            $table->foreign('id_proyek')->references('id')->on('mtr_tbl_bpd_project');
            $table->foreign('id_kelompok')->references('id')->on('mtr_tbl_bpd_kelompok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_bpd_proyek_kelompok_uangsaku');
    }
};
