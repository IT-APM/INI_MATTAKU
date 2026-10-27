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
        Schema::table('trx_tbl_ijin', function (Blueprint $table) {
            //
            $table->string('layer2')->after('atasan_property')->nullable();
            $table->string('layer2_property', 500)->after('layer2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_tbl_ijin', function (Blueprint $table) {
            //
        });
    }
};
