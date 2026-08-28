<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'name',
    'surname', 
    'nick',    
    'role',    
    'email',
    'password',
    'image',   
    'profile_photo_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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

    public function images()
    {
        return $this->hasMany(Image::class); 
    }
        public function comments() {
        // Una imagen tiene muchos comentarios
        return $this->hasMany(Comment::class);
        }
        
        public function likes() {
            // Una imagen tiene muchos likes
            return $this->hasMany(Like::class);
            }
public function likesRecibidos()
{
   
    return $this->hasManyThrough(Like::class, Image::class);
}
            // Usuarios a los que yo sigo
public function following()
{
    return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id')->withTimestamps();
}

// Usuarios que me siguen a mí
public function followers()
{
    return $this->belongsToMany(User::class, 'followers', 'followed_id', 'follower_id')->withTimestamps();
}
// Comprueba si este usuario es seguido por un ID concreto
    public function isFollowedBy($userId)
    {
       return $this->followers->contains('id', $userId);
    }
public function getRouteKeyName()
{
    return 'nick';
}
}
