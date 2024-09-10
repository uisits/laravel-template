<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('reports')
            ->table('ADMIN_BLESSED_SEMS')
            ->where('semester', '!=', '0')
            ->get()
            ->each(function($semester) {
                Semester::create([
                    'semester' => $semester->semester,
                    'blessed' => $semester->blessed === '1',
                    'roll_up' => $semester->include_in_rollups === 'Y'
                ]);
            });
    }
}
