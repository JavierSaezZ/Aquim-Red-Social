<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    // Permitimos que estas dos columnas se llenen al crear un registro
    protected $fillable = [
        'follower_id', 
        'followed_id'
    ];
}