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
        Schema::create('trx_tbl_recruitment_identitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_recruitment');
            $table->string('nama')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('kelamin')->nullable();
            $table->string('ktp')->nullable();
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('agama')->nullable();
            $table->string('npwp')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('status_hidup')->nullable();
            $table->date('tanggal_status')->nullable();
            $table->string('nama_pasangan')->nullable();
            $table->integer('usia_pasangan')->nullable();
            $table->string('kelamin_pasangan')->nullable();
            $table->string('pendidikan_pasangan')->nullable();
            $table->string('pekerjaan_pasangan')->nullable();
            $table->string('nama_bapak')->nullable();
            $table->integer('usia_bapak')->nullable();
            $table->string('pendidikan_bapak')->nullable();
            $table->string('pekerjaan_bapak')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->integer('usia_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->text('hobi')->nullable();
            $table->text('kelebihan')->nullable();
            $table->text('peningkatan')->nullable();
            $table->string('is_sakit')->nullable();
            $table->text('sakit_apa')->nullable();
            $table->integer('sakit_tahun')->nullable();
            $table->text('sakit_kondisi')->nullable();
            $table->text('alasan')->nullable();
            $table->integer('gaji')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_recruitment_identitas', function (Blueprint $table) {
            //
            $table->foreign('id_recruitment')->references('id')->on('trx_tbl_recruitment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_recruitment_identitas');
    }
};
