<?php

namespace App\Models;

use App\Enums\EvaluationType;
use App\Models\Scopes\ActiveSemesterScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Registration extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = [
        'completed_evaluation'
    ];

    protected $casts = [
        'is_uni_301' => 'boolean',
        'online' => 'boolean',
        'section_type' => EvaluationType::class,
        'evaluation_start' => 'datetime',
        'evaluation_end' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function semester(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester', 'semester');
    }

    /**
     * Returns the students completed evaluations.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function evaluation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Evaluation::class);
    }

    /**
     * Returns the students completed evaluations.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function uniEvaluation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UniEvaluation::class);
    }

    public function getCompletedEvaluationAttribute()
    {
        if(Str::of($this->course_no)->contains('UNI') ) {
            return $this->uniEvaluation?->exists();
        }
        return $this->evaluation?->exists();
    }

}
