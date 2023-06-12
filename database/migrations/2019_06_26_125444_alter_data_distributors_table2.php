<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDataDistributorsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_distributors', function (Blueprint $table) {
            $table->dropColumn('distributor');
            $table->dropColumn('cabang');
            $table->dropColumn('produk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_distributors', function (Blueprint $table) {
            //
        });
    }
}
