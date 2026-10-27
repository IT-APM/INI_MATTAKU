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
        Schema::create('trx_tbl_todolist_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trx')->nullable();
            $table->unsignedBigInteger('id_status')->nullable();
            $table->string('nik')->nullable();
            $table->string('pic')->nullable();
            $table->string('title')->nullable();
            $table->string('nama')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->string('link')->nullable();
            $table->string('filename')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trx_tbl_todolist_log');
    }
};
