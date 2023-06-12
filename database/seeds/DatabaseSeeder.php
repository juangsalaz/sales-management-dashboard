<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(dataUserSeeder::class);
        $this->call(menuSeeder::class);
        $this->call(menuJabatanSeeder::class);
        $this->call(dataDistributorSeeder::class);
        $this->call(dataRunningText::class);
        $this->call(statusSeeder::class);
    }
}
