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
        //
        Schema::table('mtr_tbl_karyawan', function (Blueprint $table) {
            $table->renameColumn('golongan_ibu_kandung', 'nama_ibu_kandung');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('mtr_tbl_karyawan', function (Blueprint $table) {
            $table->renameColumn('nama_ibu_kandung', 'golongan_ibu_kandung');
        });
    }
};
