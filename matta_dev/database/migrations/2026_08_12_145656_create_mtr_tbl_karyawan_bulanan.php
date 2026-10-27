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
        Schema::create('mtr_tbl_karyawan_bulanan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nik');
            $table->text('nik_property')->nullable();
            $table->string('layer1');
            $table->text('layer1_property')->nullable();
            $table->string('layer2');
            $table->text('layer2_property')->nullable();
            $table->text('perubahan')->nullable();
            $table->boolean('is_berubah')->default(0);
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_karyawan_bulanan', function (Blueprint $table) {
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
        Schema::dropIfExists('mtr_tbl_karyawan_bulanan');
    }
};
