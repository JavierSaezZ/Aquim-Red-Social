<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{

protected $fillable = [
        'image_id', 
        'user_id', 
        'content'
    ];

    /**
     * Relación Muchos a Uno: El comentario pertenece a un usuario.
     */
    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class);
    }

    /**
     * Relación Muchos a Uno: El comentario pertenece a una imagen.
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}