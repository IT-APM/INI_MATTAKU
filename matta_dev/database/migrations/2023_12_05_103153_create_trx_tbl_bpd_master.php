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
        Schema::create('trx_tbl_bpd_master', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_ca')->nullable();
            $table->integer('periode');
            $table->string('luar_dalam');
            $table->boolean('is_project')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('kelompok_id')->nullable();
            $table->boolean('is_leader')->nullable();
            $table->string('pengaju');
            $table->text('pengaju_property');
            $table->string('atasan');
            $table->text('atasan_property');
            $table->string('admin')->nullable();
            $table->text('admin_property')->nullable();
            $table->string('koordinator')->nullable();
            $table->text('koordinator_property')->nullable();
            $table->string('layer1')->nullable();
            $table->text('layer1_property')->nullable();
            $table->string('layer2')->nullable();
            $table->text('layer2_property')->nullable();
            $table->string('hco')->nullable();
            $table->text('hco_property')->nullable();
            $table->text('uraian')->nullable();
            $table->string('lampiran')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_bpd_master', function (Blueprint $table) {
            //
            $table->foreign('pengaju')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('atasan')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('admin')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('koordinator')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('layer1')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('layer2')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('hco')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('project_id')->references('id')->on('mtr_tbl_bpd_project');
            $table->foreign('kelompok_id')->references('id')->on('mtr_tbl_bpd_kelompok');
            $table->foreign('status_id')->references('id')->on('mtr_tbl_bpd_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_bpd_master');
    }
};
