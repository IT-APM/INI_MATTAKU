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
        Schema::create('trx_tbl_recruitment', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_kebutuhan');
            $table->unsignedBigInteger('id_status');
            $table->text('qualification')->nullable();
            $table->text('job_description')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_recruitment', function (Blueprint $table) {
            //
            $table->foreign('id_kebutuhan')->references('id')->on('trx_tbl_kebutuhan_master');
            $table->foreign('id_status')->references('id')->on('mtr_tbl_recruitment_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_recruitment');
    }
};
