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
            $table->double('ot_length_rp', 15, 2)->change();
            $table->double('ot_length_rp_adj', 15, 2)->nullable()->after('ot_length_rp');
            $table->renameColumn('ot_length_rp', 'ot_length_rp_rev');
            $table->integer('ot_length_adj')->nullable()->after('ot_length_rev');
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
