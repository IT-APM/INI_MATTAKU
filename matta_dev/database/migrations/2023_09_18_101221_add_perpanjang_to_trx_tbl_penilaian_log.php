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
        Schema::table('trx_tbl_penilaian_log', function (Blueprint $table) {
            //
            $table->boolean('is_perpanjang')->nullable()->after('keterangan');
            $table->integer('bulan')->nullable()->after('is_perpanjang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_tbl_penilaian_log', function (Blueprint $table) {
            //
        });
    }
};
