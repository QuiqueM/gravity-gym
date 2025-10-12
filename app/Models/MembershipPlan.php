<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'duration_days',
        'class_limit',
        'price',
        'requires_payment',
        'is_active',
        'is_membership_initial',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }
}


