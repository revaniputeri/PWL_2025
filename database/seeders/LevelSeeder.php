<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'level_id' => 1,
                'level_code' => 'ADM',
                'level_nama' => 'Administrator',
            ],
            [
                'level_id' => 2,
                'level_code' => 'MNG',
                'level_nama' => 'Manager',
            ],
            [
                'level_id' => 3,
                'level_code' => 'STF',
                'level_nama' => 'Staff/Kasir',
            ],
        ];

        DB::table('m_level')->insert($data);
    }
}
