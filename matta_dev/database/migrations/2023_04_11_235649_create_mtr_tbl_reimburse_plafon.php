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
        Schema::create('mtr_tbl_reimburse_plafon', function (Blueprint $table) {
            $table->id();
            $table->string('nik_karyawan');
            $table->integer('periode');
            $table->double('kesehatan', 15, 2)->default(0);
            $table->double('lensa', 15, 2)->default(0);
            $table->double('frame', 15, 2)->default(0);
            $table->double('kehamilan', 15, 2)->default(0);
            $table->double('persalinan_normal', 15, 2)->default(0);
            $table->double('persalinan_caesar', 15, 2)->default(0);
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_reimburse_plafon', function (Blueprint $table) {
            //
            $table->foreign('nik_karyawan')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_reimburse_plafon');
    }
};
