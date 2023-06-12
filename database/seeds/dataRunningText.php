<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class dataRunningText extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = Str::uuid();

        $model =new \App\running_text();
        $model->id = $id;
        $model->teks = '';
        $model->created_at = date('Y-m-d H:m:i');
        $model->updated_at = date('Y-m-d H:m:i');
        $model->save();
    }
}

