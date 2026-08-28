<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
protected $fillable = [
        'image_path',
        'description',
        'user_id' 
    ];

    /**
     * Relación One To Many (Comentarios)
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relación One To Many (Likes)
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
    
    /**
     * Relación Muchos a Uno (El usuario que subió la foto)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
