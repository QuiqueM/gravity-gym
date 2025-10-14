<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'description',
    'image',
    'class_id',
  ];

  public function gymClass()
  {
    return $this->belongsTo(GymClass::class, 'class_id');
  }

  protected function image(): Attribute
  {
    return Attribute::make(
      get: fn ($value) => $value ? Storage::url($value) : null,
    );
  }
}
