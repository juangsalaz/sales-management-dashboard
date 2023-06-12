<?php

use Illuminate\Database\Seeder;

class statusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model =new \App\Status();
        $model->id = 0;
        $model->status = 'Tidak Aktif';
        $model->save();
        $model =new \App\Status();
        $model->id = 1;
        $model->status = 'Aktif';
        $model->save();
    }
}
