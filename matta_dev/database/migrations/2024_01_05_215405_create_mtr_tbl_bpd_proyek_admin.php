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
        Schema::create('mtr_tbl_bpd_proyek_admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_proyek');
            $table->string('admin');
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_bpd_proyek_admin', function (Blueprint $table) {
            //
            $table->foreign('id_proyek')->references('id')->on('mtr_tbl_bpd_project');
            $table->foreign('admin')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_bpd_proyek_admin');
    }
};
