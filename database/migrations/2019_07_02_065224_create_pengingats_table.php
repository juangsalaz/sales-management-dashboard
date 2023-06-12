<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePengingatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengingats', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('pengingat');
            $table->string('keterangan');
            $table->integer('setiap_hari');
            $table->date('tanggal');
            $table->time('jam');
            $table->softDeletes();
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
        Schema::dropIfExists('pengingats');
    }
}
