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
        Schema::table('trx_tbl_overtime', function (Blueprint $table) {
            //
            $table->time('ot_length')->nullable()->after('ot_end_at');
            $table->dateTime('ot_end_clockin')->nullable()->after('ot_start_clockin');
            $table->dateTime('ot_start_clockout')->nullable()->after('ot_end_clockin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_tbl_overtime', function (Blueprint $table) {
            //
        });
    }
};
