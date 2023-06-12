<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDataStokTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_stoks', function (Blueprint $table) {
            $table->dropColumn('area');
            $table->dropColumn('distributor');
            $table->dropColumn('cabang');
            $table->dropColumn('produk');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_stoks', function (Blueprint $table) {
            //
        });
    }
}
