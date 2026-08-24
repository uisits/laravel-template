<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Cdm\StudentGradeHistory;
use App\Models\Cdm\StudentTransferredCourse;
use App\Models\Livedata\StudentAttendance;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class StudentClass
{
    public static function getEnrolledClasses(string $termCode, string $uin): Collection
    {
        return StudentAttendance::query()
            ->select('term_cd', 'uin', 'crn')
            ->with(['allCourses' => function (Builder $query) use ($termCode) {
                $query
                    ->select('crn', 'term_cd', 'crs_subj_cd', 'crs_nbr')
                    ->where('term_cd', $termCode);
            }])
            ->where('term_cd', $termCode)
            ->where('uin', $uin)
            ->get()
            ->map(fn ($course) => $course->allCourses->crs_subj_cd.' '.$course->allCourses->crs_nbr);
    }

    public static function getCompletedClasses(string $termCode, string $uin): Collection
    {
        return StudentGradeHistory::query()
            ->where('term_cd', $termCode)
            ->where('uin', $uin)
            ->whereIn('crs_subj_cd', ['MAT', 'CSC'])
            ->whereIn('crs_grade_cd', ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'CR', 'AA', 'ACR', 'AU', 'BB', 'CC'])
            ->get()
            ->map(function ($course) {
                if ($course->crs_subj_cd == 'MAT' && $course->crs_nbr == '102' && in_array($course->crs_grade_cd, ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'CR'])) {
                    return 'ZZ_CSC302';
                }

                return $course->crs_subj_cd.' '.$course->crs_nbr;
            });
    }

    public static function getTransferredCourses(string $uin, ?string $termCode = null)
    {
        return StudentTransferredCourse::query()
            ->where('uin', $uin)
            ->when($termCode, fn ($query) => $query->where('eq_term_cd', $termCode))
            ->whereIn('eq_crs_subj_cd', ['MAT', 'CSC'])
            ->whereIn('eq_crs_grade_cd', ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'D-', 'CR', 'AA', 'ACR', 'AU', 'BB', 'CC'])
            ->get()
            ->map(function ($course) {
                if ($course->eq_crs_subj_cd == 'MAT' && $course->eq_crs_nbr == '102' && in_array($course->eq_crs_grade_cd, ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'CR'])) {
                    return 'ZZ_CSC302';
                }

                return $course->eq_crs_subj_cd.' '.$course->fixed_eq_crs_nbr;
            });
    }
}
