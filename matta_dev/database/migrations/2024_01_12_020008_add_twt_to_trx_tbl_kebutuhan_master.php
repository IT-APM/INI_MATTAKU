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
            $table->text('tugas')->after('id_pekerjaan')->nullable();
            $table->text('wewenang')->after('id_pekerjaan')->nullable();
            $table->text('tanggung_jawab')->after('id_pekerjaan')->nullable();
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
