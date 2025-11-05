<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'face_image',
        'face_descriptor',
        'face_verified',
        'face_registered_at',
    ];

    /**
     * Hidden attributes (API response এ দেখাবে না)
     */
    protected $hidden = [
        'password',
        'remember_token',
        'face_descriptor', // Security: descriptor public করবো না
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'face_verified' => 'boolean',
        'face_registered_at' => 'datetime',
    ];

    /**
     * Face descriptor get করার সময় JSON decode করে array return করবে
     * 
     * @param string $value
     * @return array|null
     */
    public function getFaceDescriptorAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    /**
     * Face descriptor set করার সময় array কে JSON encode করবে
     * 
     * @param array|string $value
     * @return void
     */
    public function setFaceDescriptorAttribute($value)
    {
        $this->attributes['face_descriptor'] = is_array($value) 
            ? json_encode($value) 
            : $value;
    }

    /**
     * Face image এর full URL return করবে
     * 
     * @return string|null
     */
    public function getFaceImageUrlAttribute()
    {
        if (!$this->face_image) {
            return null;
        }

        return Storage::disk('public')->url($this->face_image);
    }

    /**
     * Check করবে user এর face verification setup করা আছে কিনা
     * 
     * @return bool
     */
    public function hasFaceVerification(): bool
    {
        return $this->face_verified && 
               !empty($this->face_descriptor) && 
               !empty($this->face_image);
    }

    /**
     * User এর face image delete করবে
     * 
     * @return bool
     */
    public function deleteFaceImage(): bool
    {
        if ($this->face_image && Storage::disk('public')->exists($this->face_image)) {
            return Storage::disk('public')->delete($this->face_image);
        }

        return false;
    }

    /**
     * Face verification data reset করবে
     * 
     * @return bool
     */
    public function resetFaceVerification(): bool
    {
        $this->deleteFaceImage();

        return $this->update([
            'face_image' => null,
            'face_descriptor' => null,
            'face_verified' => false,
            'face_registered_at' => null,
        ]);
    }
}