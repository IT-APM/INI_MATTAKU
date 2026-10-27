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
        Schema::create('mtr_tbl_bpd_nik_bod', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('layer1');
            $table->string('layer2');
            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_bpd_nik_bod', function (Blueprint $table) {
            //
            $table->foreign('nik')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('layer1')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('layer2')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_bpd_nik_bod');
    }
};
