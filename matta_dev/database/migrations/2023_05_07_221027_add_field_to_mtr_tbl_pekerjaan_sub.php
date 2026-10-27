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
        Schema::table('mtr_tbl_pekerjaan_sub', function (Blueprint $table) {
            //
            $table->text('tugas')->after('pekerjaan');
            $table->text('wewenang')->after('tugas');
            $table->text('tanggung_jawab')->after('wewenang');
            $table->text('keterangan')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mtr_tbl_pekerjaan_sub', function (Blueprint $table) {
            //
        });
    }
};
