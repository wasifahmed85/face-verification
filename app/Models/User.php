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
     * The attributes that are mass assignable.
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
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'face_descriptor', // Security: do not expose descriptor publicly
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'face_verified' => 'boolean',
        'face_registered_at' => 'datetime',
    ];

    /**
     * Decode face descriptor from JSON to array when accessed.
     *
     * @param string|null $value
     * @return array|null
     */
    public function getFaceDescriptorAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    /**
     * Encode face descriptor to JSON when set.
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
     * Get the full URL of the user's face image.
     *
     * @return string|null
     */
    // public function getFaceImageUrlAttribute()
    // {
    //     if (!$this->face_image) {
    //         return null;
    //     }

    //     return Storage::disk('public')->url($this->face_image);
    // }

    /**
     * Check if the user has face verification data set up.
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
     * Delete the user's stored face image.
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
     * Reset the user's face verification data.
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
