<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTargetDistributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_distributors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->String('bulan');
            $table->integer('tahun');
            $table->uuid('distributor');
            $table->uuid('user');
            $table->uuid('area');
            $table->integer('target');
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
        Schema::dropIfExists('target_distributors');
    }
}
