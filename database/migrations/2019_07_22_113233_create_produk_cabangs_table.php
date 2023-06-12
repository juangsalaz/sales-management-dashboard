<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateProdukCabangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk_cabangs', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('produk_id');
            $table->uuid('coverage_area_id');
            $table->uuid('cabang_id');
            $table->integer('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produk_cabangs', function (Blueprint $table) {
            //
        });
    }
}
