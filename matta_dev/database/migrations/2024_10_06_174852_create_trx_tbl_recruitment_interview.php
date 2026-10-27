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
        Schema::create('trx_tbl_recruitment_interview', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_identitas');
            $table->unsignedBigInteger('id_status');
            $table->string('jenis');
            $table->string('interviewer')->nullable();
            $table->text('interviewer_property')->nullable();
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->string('file_interview')->nullable();
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_recruitment_interview', function (Blueprint $table) {
            //
            $table->foreign('id_identitas')->references('id')->on('trx_tbl_recruitment_identitas');
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
        Schema::dropIfExists('trx_tbl_recruitment_interview');
    }
};
