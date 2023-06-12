<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateEstimasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->String('bulan');
            $table->integer('tahun');
            $table->uuid('produk');
            $table->uuid('nama_user');
            $table->uuid('distributor');
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
        Schema::dropIfExists('estimasis');
    }
}
