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
        Schema::table('mtr_tbl_bpd_project', function (Blueprint $table) {
            //
            $table->string('admin')->after('group');
            $table->foreign('admin')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mtr_tbl_bpd_project', function (Blueprint $table) {
            //
        });
    }
};
