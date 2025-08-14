<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description',
    ];

    public function classes(): HasMany
    {
        return $this->hasMany(GymClass::class, 'class_type_id');
    }
}


