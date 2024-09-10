<?php

namespace Database\Seeders;

use App\Models\Registration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('course-evals')
            ->table('V_REGISTRATION AS R')
            ->leftJoin('WK_COURSES AS C', function ($join) {
                $join->on('R.COURSE', '=', 'C.COURSENAME')
                    ->on('R.SEMESTER', '=', 'C.SEMESTER')
                    ->on('R.SECTIONTYPE', '=', 'C.SECTIONTYPE');
            })
            ->orderBy('R.course')
            ->each(function($registration) {
                Registration::create([
                    'student_firstname' => Str::after($registration->studentname, ','),
                    'student_lastname' => Str::before($registration->studentname, ','),
                    'student_netid' => $registration->netid,
                    'course_no' => $registration->course,
                    'course_name' => $registration->title,
                    'instructor_name' => $registration->instructor,
                    'instructor_uin' => $registration->instructoruin,
                    'instructor_netid' => $registration->instructornetid,
                    'fcrn' => $registration->fcrn,
                    'section_type' => Str::lower($registration->sectiontypetxt),
                    'online' => $registration->onlinecourse === 'true' ? 1 : 0,
                    'semester' => $registration->semester,
                    'evaluation_start' => $registration->eval_start,
                    'evaluation_end' => $registration->eval_end,
                ]);
            });
    }
}
