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
            $table->unsignedBigInteger('id_penambahan')->nullable()->change();
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
