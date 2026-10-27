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
        Schema::create('mtr_tbl_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('password');
            $table->string('nama');
            $table->string('layer1')->nullable();
            $table->string('layer2')->nullable();
            $table->string('layer3')->nullable();
            $table->boolean('is_aktif')->default(1);
            $table->unsignedBigInteger('kode_status')->nullable();
            $table->string('email')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat_domisili', 1000)->nullable();
            $table->string('alamat_ktp', 1000)->nullable();
            $table->string('alamat_npwp', 1000)->nullable();
            $table->string('telepon1')->nullable();
            $table->string('telepon2')->nullable();
            $table->string('telepon3')->nullable();
            $table->string('telepon_darurat')->nullable();
            $table->string('alamat_kantor')->nullable();
            $table->unsignedBigInteger('wg_id');
            $table->string('mobile_id')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('no_ktp')->nullable();
            $table->string('nama_pasangan')->nullable();
            $table->string('nama_ibu_kandung')->nullable();
            $table->string('golongan_darah')->nullable();
            $table->unsignedBigInteger('kode_agama')->nullable();
            $table->unsignedBigInteger('kode_kelamin')->nullable();
            $table->unsignedBigInteger('kode_warga')->nullable();
            $table->unsignedBigInteger('kode_kawin')->nullable();
            $table->unsignedBigInteger('kode_perusahaan');
            $table->unsignedBigInteger('kode_divisi');
            $table->unsignedBigInteger('kode_departemen');
            $table->unsignedBigInteger('kode_sub_departemen');
            $table->unsignedBigInteger('kode_level');
            $table->unsignedBigInteger('kode_jabatan');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::table('mtr_tbl_karyawan', function (Blueprint $table) {
            //
            $table->foreign('kode_status')->references('id')->on('mtr_tbl_status_karyawan');
            $table->foreign('kode_perusahaan')->references('id')->on('mtr_tbl_perusahaan');
            $table->foreign('kode_divisi')->references('id')->on('mtr_tbl_divisi');
            $table->foreign('kode_departemen')->references('id')->on('mtr_tbl_departemen');
            $table->foreign('kode_sub_departemen')->references('id')->on('mtr_tbl_sub_departemen');
            $table->foreign('kode_agama')->references('id')->on('mtr_tbl_agama');
            $table->foreign('kode_kelamin')->references('id')->on('mtr_tbl_kelamin');
            $table->foreign('kode_warga')->references('id')->on('mtr_tbl_warga');
            $table->foreign('kode_kawin')->references('id')->on('mtr_tbl_kawin');
            $table->foreign('kode_level')->references('id')->on('mtr_tbl_level');
            $table->foreign('kode_jabatan')->references('id')->on('mtr_tbl_jabatan');
            $table->foreign('wg_id')->references('id')->on('mtr_tbl_working_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_karyawan');
    }
};
