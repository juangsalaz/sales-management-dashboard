<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDataDistributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_distributors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->String('nama_user');
            $table->uuid('coverage_area');
            $table->uuid('distributor');
            $table->uuid('cabang');
            $table->uuid('produk');
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
        Schema::dropIfExists('data_distributors');
    }
}
