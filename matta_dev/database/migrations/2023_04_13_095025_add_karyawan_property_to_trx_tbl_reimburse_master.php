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
        Schema::table('trx_tbl_reimburse_master', function (Blueprint $table) {
            //
            $table->string('karyawan_property', 500)->nullable()->after('nik_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_tbl_reimburse_master', function (Blueprint $table) {
            //
        });
    }
};
