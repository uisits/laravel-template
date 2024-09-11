<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    /**
     * Returns all the registrations for the semester
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Registration::class, 'semester', 'semester');
    }

}
