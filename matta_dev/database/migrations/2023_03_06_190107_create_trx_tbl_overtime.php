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
        Schema::create('trx_tbl_overtime', function (Blueprint $table) {
            $table->id();
            $table->dateTime('ot_maker_at');
            $table->dateTime('ot_approver_at')->nullable();
            $table->string('ot_maker_by');
            $table->string('ot_approver_by');
            $table->string('ot_maker_status')->nullable();
            $table->string('ot_approver_status')->nullable();
            $table->dateTime('ot_start_at');
            $table->dateTime('ot_end_at');
            $table->dateTime('ot_start_clockin');
            $table->dateTime('ot_end_clockout');
            $table->string('ot_title');
            $table->string('ot_desc', 1000)->nullable();
            $table->unsignedBigInteger('ot_type_code');
            $table->dateTime('ot_start_at_real')->nullable();
            $table->dateTime('ot_end_at_real')->nullable();
            $table->time('ot_length_real')->nullable();
            $table->dateTime('ot_start_at_rev')->nullable();
            $table->dateTime('ot_end_at_rev')->nullable();
            $table->time('ot_length_rev')->nullable();
            $table->double('ot_length_rp', 15, 8)->nullable()->default(0);
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_overtime', function (Blueprint $table) {
            //
            $table->foreign('ot_type_code')->references('id')->on('mtr_tbl_overtime_type');
            $table->foreign('ot_maker_by')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('ot_approver_by')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_overtime');
    }
};
