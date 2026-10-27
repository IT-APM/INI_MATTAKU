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
        Schema::create('trx_tbl_attendance', function (Blueprint $table) {
            $table->id();
            $table->date('att_date');
            $table->dateTime('att_timestamp');
            $table->string('att_nik');
            $table->string('att_type');
            $table->double('att_long', 15, 8)->nullable()->default(0);
            $table->double('att_lat', 15, 8)->nullable()->default(0);
            $table->string('att_address', 1000)->nullable();
            $table->double('att_ref_long', 15, 8)->nullable()->default(0);
            $table->double('att_ref_lat', 15, 8)->nullable()->default(0);
            $table->string('att_ref_address', 1000)->nullable();
            $table->double('att_distance_gap', 15, 8)->nullable()->default(0);
            $table->time('att_clock_gap')->nullable();
            $table->string('att_status')->nullable();
            $table->string('att_data_src')->nullable();
            $table->unsignedBigInteger('att_wd_ot_id')->default(0);
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_attendance', function (Blueprint $table) {
            //
            $table->foreign('att_nik')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_attendance');
    }
};
