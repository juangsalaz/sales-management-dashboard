<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDeathStoksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('death_stoks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->String('produk');
            $table->uuid('user');
            $table->uuid('area');
            $table->uuid('distributor');
            $table->uuid('cabang');
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
        Schema::dropIfExists('death_stoks');
    }
}
