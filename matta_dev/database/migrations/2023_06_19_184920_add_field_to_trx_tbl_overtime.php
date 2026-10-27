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
        Schema::table('trx_tbl_overtime', function (Blueprint $table) {
            //
            $table->string('ot_maker_property', 500)->after('ot_maker_by')->nullable();
            $table->string('ot_approver_property', 500)->after('ot_approver_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trx_tbl_overtime', function (Blueprint $table) {
            //
        });
    }
};
