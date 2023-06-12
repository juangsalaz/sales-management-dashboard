<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class dataUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bagian_id = Str::uuid();
        $jabatan_id = Str::uuid();
        $user_id = Str::uuid();

        $bagian_id2 = Str::uuid();
        $jabatan_id2 = Str::uuid();
        $user_id2 = Str::uuid();

        $bagian_id3 = Str::uuid();
        $jabatan_id3 = Str::uuid();
        $user_id3 = Str::uuid();

        $model =new \App\bagian();
        $model->id = $bagian_id;
        $model->nama_bagian = 'Super User';
        $model->keterangan = 'Bagian Super User';
        $model->save();
        $id_bagian = $model->select('id')->where('nama_bagian','Super User')->get();

        $model =new \App\jabatan();
        $model->id = $jabatan_id;
        $model->nama_jabatan = 'Super User';
        $model->keterangan = 'Jabatan Super User';
        $model->save();
        $id_jabatan = $model->select('id')->where('nama_jabatan','Super User')->get();

        $model =new \App\daftar_user();
        $model->id = $user_id;
        $model->nama_user = 'Super User';
        $model->username = 'superuser';
        $model->bagian = $id_bagian[0]['id'];
        $model->jabatan = $id_jabatan[0]['id'];
        $model->status = 1;
        $model->password = Hash::make('admin');
        $model->save();

        $model =new \App\bagian();
        $model->id = $bagian_id2;
        $model->nama_bagian = 'Direktur';
        $model->keterangan = 'Direktur Perusahaan';
        $model->save();
        $id_bagian = $model->select('id')->where('nama_bagian','Direktur')->get();

        $model =new \App\jabatan();
        $model->id = $jabatan_id2;
        $model->nama_jabatan = 'Direktur';
        $model->keterangan = 'Direktur Perusahaan';
        $model->save();
        $id_jabatan = $model->select('id')->where('nama_jabatan','Direktur')->get();

        $model =new \App\daftar_user();
        $model->id = $user_id2;
        $model->nama_user = 'Direktur';
        $model->username = 'direktur';
        $model->bagian = $id_bagian[0]['id'];
        $model->jabatan = $id_jabatan[0]['id'];
        $model->status = 1;
        $model->password = Hash::make('direktur');
        $model->save();

        $model =new \App\bagian();
        $model->id = $bagian_id3;
        $model->nama_bagian = 'IT Development';
        $model->keterangan = 'IT development Perusahaan';
        $model->save();
        $id_bagian = $model->select('id')->where('nama_bagian','IT Development')->get();

        $model =new \App\jabatan();
        $model->id = $jabatan_id3;
        $model->nama_jabatan = 'Staff';
        $model->keterangan = 'Staff Perusahaan';
        $model->save();
        $id_jabatan = $model->select('id')->where('nama_jabatan','Staff')->get();

        $model =new \App\daftar_user();
        $model->id = $user_id3;
        $model->nama_user = 'Juang Salaz Prabowo';
        $model->username = 'juang';
        $model->bagian = $id_bagian[0]['id'];
        $model->jabatan = $id_jabatan[0]['id'];
        $model->status = 1;
        $model->password = Hash::make('juang123');
        $model->save();
    }
}
