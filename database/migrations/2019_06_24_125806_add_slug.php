<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_utamas', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->string('slug_icon')->nullable();
        });
        Schema::table('sub_menu1s', function (Blueprint $table) {
            $table->string('slug');
            $table->string('slug_icon')->nullable();
        });
        Schema::table('sub_menu2s', function (Blueprint $table) {
            $table->string('slug');
            $table->string('slug_icon')->nullable();
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
            $table->dropColumn('slug');
            $table->dropColumn('slug_icon');
        });
        Schema::table('sub_menu1s', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('slug_icon');
        });
        Schema::table('sub_menu2s', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('slug_icon');
        });
    }
}
