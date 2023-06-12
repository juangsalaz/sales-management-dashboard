<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateDaftarUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daftar_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_user');
            $table->string('username');
            $table->uuid('bagian');
            $table->string('password');
            $table->uuid('jabatan');
            $table->integer('status');
            $table->rememberToken()->nullable();
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
        Schema::dropIfExists('daftar_users');
    }
}
