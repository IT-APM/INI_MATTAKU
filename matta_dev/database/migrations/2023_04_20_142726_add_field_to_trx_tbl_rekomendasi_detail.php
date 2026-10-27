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
        Schema::table('trx_tbl_rekomendasi_detail', function (Blueprint $table) {
            //
            $table->string('jenis_pengajuan')->after('id_pengajuan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_tbl_rekomendasi_detail', function (Blueprint $table) {
            //
        });
    }
};
