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
        Schema::create('trx_tbl_birthday', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->text('nik_property')->nullable();
            $table->text('email')->nullable();
            $table->date('birthday')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_birthday', function (Blueprint $table) {
            $table->foreign('nik')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_birthday');
    }
};
