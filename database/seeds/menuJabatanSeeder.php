<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;

class menuJabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model =new \App\jabatan();
        $id_direktur = $model->select('id')->where('nama_jabatan','Direktur')->get();

        $model =new \App\menu_utama();
        $list_menu = $model->all();
        $model =new \App\sub_menu1();
        $list_sub_menu1 = $model->all();
        $model =new \App\sub_menu2();
        $list_sub_menu2 = $model->all();
        $menu_jabatan =new \App\menu_jabatan();
        $now = Carbon::now()->toDateTimeString();

        foreach ($list_menu as $menu) {
            foreach ($list_sub_menu1 as $sub_menu1) {
                if ($menu['id']==$sub_menu1['id_menu_utama']) {
                    if($sub_menu1['nama']=='Daftar Distributor'){
                        $menu_jabatan->insert(['id' => Str::uuid(), 'id_jabatan'=>$id_direktur[0]['id'],'id_menu'=>$menu['id'],'id_sub_menu1'=>$sub_menu1['id'],'created_at'=>$now,'updated_at'=>$now]);
                    } else {
                        foreach ($list_sub_menu2 as $sub_menu2) {
                            if ($sub_menu1['id']==$sub_menu2['id_sub_menu1']) {
                                $menu_jabatan->insert(['id' => Str::uuid(), 'id_jabatan'=>$id_direktur[0]['id'],'id_menu'=>$menu['id'],'id_sub_menu1'=>$sub_menu1['id'],'id_sub_menu2'=>$sub_menu2['id'],'created_at'=>$now,'updated_at'=>$now]);
                            }
                        }
                    }
                }
            }
        }

        $sub_menu =new \App\sub_menu1();
        $sub_menus = $sub_menu->select('id_menu_utama')->distinct()->get();
        $model =new \App\menu_utama();
        $menu1 = $model->whereNotIn('id',$sub_menus)->get();
        foreach ($menu1 as $menu) {
            $menu_jabatan->insert(['id' => Str::uuid(), 'id_jabatan'=>$id_direktur[0]['id'],'id_menu'=>$menu['id'],'created_at'=>$now,'updated_at'=>$now]);
        }
        // $datas = $model->select('menu_utamas.nama as nama_menu','menu_utamas.id as menu_utama','sub_menu1s.nama as nama_sub_menu1','sub_menu1s.id as sub_menu','sub_menu2s.nama as nama_sub_menu2','sub_menu2s.id as sub_menu2')->leftJoin('sub_menu1s','menu_utamas.id','=','sub_menu1s.id_menu_utama')->leftJoin('sub_menu2s','sub_menu1s.id','=','sub_menu2s.id_sub_menu1')->get();
    }
}
