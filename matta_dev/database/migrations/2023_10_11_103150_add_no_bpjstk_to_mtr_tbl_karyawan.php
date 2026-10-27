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
        Schema::table('mtr_tbl_karyawan', function (Blueprint $table) {
            //
            $table->string('no_bpjstk')->nullable()->after('no_bpjs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mtr_tbl_karyawan', function (Blueprint $table) {
            //
        });
    }
};
