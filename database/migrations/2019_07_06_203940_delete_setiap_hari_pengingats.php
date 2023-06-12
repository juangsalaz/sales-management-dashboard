<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteSetiapHariPengingats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengingats', function (Blueprint $table) {
            $table->dropColumn('setiap_hari');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('pengingats', function (Blueprint $table) {
        //     $table->integer('setiap_hari');
        // });
    }
}
