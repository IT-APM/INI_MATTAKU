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
        Schema::create('trx_tbl_feeds', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content')->nullable();
            $table->boolean('is_active')->default(0);
            $table->boolean('is_notif')->default(0);
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();
        });
        
        Schema::table('trx_tbl_feeds', function (Blueprint $table) {
            $table->foreign('created_by')->references('nik')->on('mtr_tbl_karyawan');
            $table->foreign('updated_by')->references('nik')->on('mtr_tbl_karyawan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_feeds');
    }
};
