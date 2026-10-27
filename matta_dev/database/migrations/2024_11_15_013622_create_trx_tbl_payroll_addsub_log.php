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
        Schema::create('trx_tbl_payroll_addsub_log', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->double('angka', 15, 2)->default(0);
            $table->unsignedBigInteger('id_addsub');
            $table->string('tipe');
            $table->boolean('status')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_payroll_addsub_log', function (Blueprint $table) {
            $table->foreign('nik')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('id_addsub')->references('id')->on('mtr_tbl_payroll_addsub');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_payroll_addsub_log');
    }
};
