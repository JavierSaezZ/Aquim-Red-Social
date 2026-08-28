<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
protected $fillable = [
        'id',
        'user_id',
        'image_id'
    ];


    /**
     * Relación Muchos a Uno: El like pertenece a un usuario.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación Muchos a Uno: El like pertenece a una imagen.
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}