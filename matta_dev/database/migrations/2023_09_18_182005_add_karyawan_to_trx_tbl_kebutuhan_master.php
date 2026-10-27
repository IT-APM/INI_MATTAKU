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
        Schema::table('trx_tbl_kebutuhan_master', function (Blueprint $table) {
            //
            $table->string('nik_karyawan')->nullable()->after('id_jenis_permintaan');
            $table->text('nik_karyawan_property')->nullable()->after('nik_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_tbl_kebutuhan_master', function (Blueprint $table) {
            //
        });
    }
};
