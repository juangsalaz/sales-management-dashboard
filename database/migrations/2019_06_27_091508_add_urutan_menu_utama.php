<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrutanMenuUtama extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_utamas', function (Blueprint $table) {
            $table->integer('urutan')->nullable();
        });
        Schema::table('sub_menu1s', function (Blueprint $table) {
            $table->integer('urutan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_utamas', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
        Schema::table('sub_menu1s', function (Blueprint $table) {
            $table->dropColumn('urutan');
        });
    }
}
