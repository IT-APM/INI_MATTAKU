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
        Schema::create('trx_tbl_overtime_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ot');
            $table->string('ot_approver_status');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_overtime_log', function (Blueprint $table) {
            //
            $table->foreign('id_ot')->references('id')->on('trx_tbl_overtime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_trx_tbl_overtime_log');
    }
};
