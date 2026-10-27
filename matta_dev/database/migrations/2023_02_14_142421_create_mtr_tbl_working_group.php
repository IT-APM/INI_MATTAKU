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
        Schema::create('mtr_tbl_working_group', function (Blueprint $table) {
            $table->id();
            $table->string('wg_name');
            $table->time('clockin');
            $table->time('clockout');
            $table->time('start_clockin');
            $table->time('end_clockin');
            $table->time('start_clockout');
            $table->time('end_clockout');
            $table->boolean('is_reguler')->default(1);
            $table->integer('ot_weekday');
            $table->integer('ot_weekend');
            $table->integer('wg_ot_monthly');
            $table->integer('wg_ot_weekly');
            $table->integer('wg_ot_daily');
            $table->double('wg_long', 15, 8)->nullable()->default(0);
            $table->double('wg_lat', 15, 8)->nullable()->default(0);
            $table->string('wg_address', 1000)->nullable();
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
        Schema::dropIfExists('mtr_tbl_working_group');
    }
};
