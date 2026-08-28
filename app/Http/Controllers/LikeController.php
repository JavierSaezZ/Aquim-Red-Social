<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;

class LikeController extends Controller
{
    /* =========================================================
       GESTIÓN DE ME GUSTA
       ========================================================= */

    public function likesuma($iduser, $idimage)
    {
        // Comprueba si el usuario ya ha marcado esta imagen con Me gusta.
        $likeExistente = \App\Models\Like::where('user_id', $iduser)
            ->where('image_id', $idimage)
            ->first();

        // Si ya existe, elimina el registro para alternar el estado.
        if ($likeExistente) {
            $likeExistente->delete();

            return response()->json([
                'status' => 'removed',
                'message' => 'Like eliminado correctamente'
            ], 200);
        }

        // Si no existe, crea el nuevo Me gusta.
        $nuevoLike = \App\Models\Like::create([
            'user_id' => $iduser,
            'image_id' => $idimage
        ]);

        return response()->json([
            'status' => 'added',
            'message' => 'Like guardado con éxito',
            'like' => $nuevoLike
        ], 201);
    }

    /*
    public function likesuma(Request $request, $iduser, $idimage)
    {
        return response()->json(['mensaje' => 'funciona']);
    }
    */
}