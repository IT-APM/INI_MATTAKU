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
        Schema::table('trx_tbl_penilaian', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('is_perpanjang')->default(0)->change();
            $table->foreign('is_perpanjang')->references('id')->on('mtr_tbl_penilaian_perpanjang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_tbl_penilaian', function (Blueprint $table) {
            //
        });
    }
};
