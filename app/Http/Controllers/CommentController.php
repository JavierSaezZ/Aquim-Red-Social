<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /* =========================================================
       CREACIÓN DE COMENTARIOS
       ========================================================= */

    public function store(Request $request, $id)
    {
        $contenido = $request->input('contenido', '');

        // Normaliza los saltos de línea y limita los bloques de líneas vacías.
        $contenido = str_replace(["\r\n", "\r"], "\n", $contenido);
        $contenido = preg_replace("/\n{3,}/", "\n\n", $contenido);
        $contenido = preg_replace("/^\n+/", "", $contenido);
        $contenido = preg_replace("/\n+$/", "", $contenido);

        $request->merge([
            'contenido' => $contenido,
        ]);

        $request->validate([
            'contenido' => 'required|string|max:2200',
        ]);

        Comment::create([
            'image_id' => $id,
            'user_id' => auth()->id(),
            'content' => $request->contenido,
        ]);

        return back();
    }


    /* =========================================================
       ELIMINACIÓN DE COMENTARIOS
       ========================================================= */

    public function delete(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comentario borrado correctamente!');
    }
}