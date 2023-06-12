<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class dataDistributorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id_distributor1 = Str::uuid();
        $id_distributor2 = Str::uuid();
        $id_distributor3 = Str::uuid();

        $model =new \App\distributor();
        $model->id = $id_distributor1;
        $model->nama_distributor = 'RNI';
        $model->created_at = date('Y-m-d');
        $model->updated_at = date('Y-m-d');
        $model->save();

        $model =new \App\distributor();
        $model->id = $id_distributor2;
        $model->nama_distributor = 'MBS';
        $model->created_at = date('Y-m-d');
        $model->updated_at = date('Y-m-d');
        $model->save();

        $model =new \App\distributor();
        $model->id = $id_distributor3;
        $model->nama_distributor = 'IGM';
        $model->created_at = date('Y-m-d');
        $model->updated_at = date('Y-m-d');
        $model->save();
    }
}
