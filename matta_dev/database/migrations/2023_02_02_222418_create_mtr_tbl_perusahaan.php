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
        Schema::create('mtr_tbl_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nama_singkat')->nullable();
            $table->string('alamat', 1000)->nullable();
            $table->double('longitude', 15, 8)->nullable()->default(0);
            $table->double('latitude', 15, 8)->nullable()->default(0);
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
        Schema::dropIfExists('mtr_tbl_perusahaan');
    }
};
