<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEstimasisTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimasis', function (Blueprint $table) {
            $table->uuid('coverage_area_id');
            $table->uuid('cabang_id');
            $table->uuid('distributor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimasis', function (Blueprint $table) {
            //
        });
    }
}
