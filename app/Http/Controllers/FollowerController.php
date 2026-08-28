<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollowerController extends Controller
{
    /* =========================================================
       GESTIÓN DE SEGUIMIENTOS
       ========================================================= */

    public function followed($follower_id, $followed_id)
    {
        // Comprueba si ya existe la relación entre ambos usuarios.
        $FollowExistente = \App\Models\Follower::where('follower_id', $follower_id)
            ->where('followed_id', $followed_id)
            ->first();

        // Si ya existe, elimina la relación para dejar de seguir al usuario.
        if ($FollowExistente) {
            $FollowExistente->delete();

            return response()->json([
                'status' => 'removed',
                'message' => 'Like eliminado correctamente'
            ], 200);
        }

        // Si no existe, crea la nueva relación de seguimiento.
        $nuevoLike = \App\Models\Follower::create([
            'follower_id' => $follower_id,
            'followed_id' => $followed_id
        ]);

        return response()->json([
            'status' => 'added',
            'message' => 'Like guardado con éxito',
            'like' => $nuevoLike
        ], 201);
    }
}