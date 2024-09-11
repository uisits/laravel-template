<?php

namespace App\Models;

use App\Enums\Evaluations\Question1Options;
use App\Enums\Evaluations\Question2Options;
use App\Enums\Evaluations\Question3Options;
use App\Enums\Evaluations\Question4Options;
use App\Enums\Evaluations\Question5Options;
use App\Enums\Evaluations\Question6Options;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evaluation extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'answer_1' => Question1Options::class,
        'answer_2' => Question2Options::class,
        'answer_3' => Question3Options::class,
        'answer_4' => Question4Options::class,
        'answer_5' => 'array',
        'answer_6' => Question6Options::class,
        'answer_7' => Question6Options::class,
        'answer_8' => Question6Options::class,
        'answer_9' => Question6Options::class,
        'answer_10' => Question6Options::class,
        'answer_11' => Question6Options::class,
        'answer_12' => Question6Options::class,
        'answer_13' => Question6Options::class,
        'answer_14' => Question6Options::class,
        'answer_15' => Question6Options::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registration(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}
