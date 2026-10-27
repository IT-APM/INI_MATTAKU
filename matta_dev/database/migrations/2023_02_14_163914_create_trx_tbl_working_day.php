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
        Schema::create('trx_tbl_working_day', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wg_id');
            $table->tinyInteger('shift');
            $table->string('nik');
            $table->date('working_date');
            $table->dateTime('clockin');
            $table->dateTime('clockout');
            $table->dateTime('start_clockin');
            $table->dateTime('end_clockin');
            $table->dateTime('start_clockout');
            $table->dateTime('end_clockout');
            $table->timestamps();
        });

        Schema::table('trx_tbl_working_day', function (Blueprint $table) {
            //
            $table->foreign('wg_id')->references('id')->on('mtr_tbl_working_group');
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
        Schema::dropIfExists('trx_tbl_working_day');
    }
};
