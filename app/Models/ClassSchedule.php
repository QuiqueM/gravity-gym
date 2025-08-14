<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'starts_at',
        'ends_at',
        'room',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(GymClass::class, 'class_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(ClassRegistration::class);
    }
}


