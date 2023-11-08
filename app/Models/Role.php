<?php

namespace App\Models;

class Role extends \Spatie\Permission\Models\Role
{
    /**
     * @var
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
