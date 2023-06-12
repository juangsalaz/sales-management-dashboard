<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRealsalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('real_sales', function (Blueprint $table) {
            $table->integer('harga_produk');
            $table->integer('kuantiti');
            $table->integer('value_gross');
            $table->integer('diskon');
            $table->integer('diskon_value');
            $table->integer('value_nett');
            $table->string('status');
            $table->uuid('outlet');
            $table->softDeletes();
            $table->dropColumn('distributor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('real_sales', function (Blueprint $table) {
            //
        });
    }
}
