<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Obtener la membresía del usuario (relación uno a uno).
     */
    public function membership()
    {
        return $this->hasOne(Membership::class);
    }

    /**
     * Obtener todas las membresías del usuario (relación uno a muchos).
     */
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Obtener todos los pagos del usuario.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Obtener el último pago del usuario.
     */
    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latest();
    }

    /**
     * Verificar si el usuario tiene una membresía activa.
     */
    public function hasActiveMembership()
    {
        return $this->membership()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->exists();
    }

    /**
     * Obtener la membresía activa del usuario.
     */
    public function activeMembership()
    {
        return $this->membership()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->first();
    }
}
