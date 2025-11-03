<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
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
        'avatar',
        'qr_code',
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
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Obtener la membresía activa del usuario.
     */
    public function activeMembership()
    {
        return $this->membership()
            ->where('is_active', true)
            ->first();
    }

    /**
     * Envía la notificación de verificación de correo electrónico.
     */
    public function sendEmailVerificationNotification(): void
    {
        // En lugar de usar la notificación por defecto,
        $this->notify(new CustomVerifyEmail);
    }

    /**
     * Envía la notificación de restablecimiento de contraseña.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
    
    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : null,
        );
    }

    protected function qrCode(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Storage::url($value) : null,
        );
    }

    /**
     * Obtener la reseña del usuario (relación uno a uno).
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function classRegistrations()
    {
        return $this->hasMany(ClassRegistration::class);
    }

    /**
     * Obtener las clases que imparte el usuario (como instructor).
     */
    public function instructedClasses()
    {
        return $this->hasMany(GymClass::class, 'instructor_id');
    }
}
