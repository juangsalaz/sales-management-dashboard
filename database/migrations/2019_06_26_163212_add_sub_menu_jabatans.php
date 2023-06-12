<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubMenuJabatans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_jabatans', function (Blueprint $table) {
            $table->uuid('id_sub_menu1')->nullable();
            $table->uuid('id_sub_menu2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_jabatans', function (Blueprint $table) {
            $table->dropColumn('id_sub_menu1');
            $table->dropColumn('id_sub_menu2');
        });
    }
}
