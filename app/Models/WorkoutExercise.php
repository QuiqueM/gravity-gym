<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'routine_id',
        'exercise',
        'sets',
        'reps',
        'duration_seconds',
        'order',
        'notes',
    ];

    public function routine(): BelongsTo
    {
        return $this->belongsTo(WorkoutRoutine::class, 'routine_id');
    }
}


