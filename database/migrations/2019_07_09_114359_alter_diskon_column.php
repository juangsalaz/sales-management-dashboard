<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDiskonColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimasis', function (Blueprint $table) {
            $table->float('diskon')->change();
        });
        Schema::table('real_sales', function (Blueprint $table) {
            $table->float('diskon')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimasis', function (Blueprint $table) {
            $table->integer('diskon')->change();
        });
        Schema::table('real_sales', function (Blueprint $table) {
            $table->integer('diskon')->change();
        });
    }
}
