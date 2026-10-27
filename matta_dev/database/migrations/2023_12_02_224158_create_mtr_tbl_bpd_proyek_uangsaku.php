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
        Schema::create('mtr_tbl_bpd_proyek_uangsaku', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_propinsi');
            $table->integer('awal');
            $table->integer('akhir');
            $table->double('uangsaku_1', 15, 2)->default(0);
            $table->double('uangsaku_2', 15, 2)->default(0);
            $table->double('uangsaku_3', 15, 2)->default(0);
            $table->double('uangsaku_4', 15, 2)->default(0);
            $table->double('uangsaku_5', 15, 2)->default(0);
            $table->timestamps();
        });
        
        Schema::table('mtr_tbl_bpd_proyek_uangsaku', function (Blueprint $table) {
            //
            $table->foreign('id_propinsi')->references('id')->on('mtr_tbl_propinsi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mtr_tbl_bpd_proyek_uangsaku');
    }
};
