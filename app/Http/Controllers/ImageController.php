<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Image;
use App\Models\Like;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class ImageController extends Controller
{
    /* =========================================================
       FEED DE PUBLICACIONES
       ========================================================= */

    public function imagenesIndex(Request $request)
    {
        $images = Image::with('user')
            ->withCount(['likes', 'comments'])
            ->latest()
            ->paginate(10);

        $userId = auth()->id();

        // Añade a cada publicación el estado de Me gusta del usuario autenticado.
        $images->getCollection()->transform(function ($image) use ($userId) {
            if ($userId) {
                $image->has_liked = Like::where('user_id', $userId)
                    ->where('image_id', $image->id)
                    ->exists();
            } else {
                $image->has_liked = false;
            }


            return $image;

            return $image;
        });

        /* Actividad reciente de usuarios seguidos
   --------------------------------------------------------- */

        $recentActivity = collect();

        if ($userId) {
            $followedIds = \DB::table('followers')
                ->where('follower_id', $userId)
                ->pluck('followed_id');

            if ($followedIds->isNotEmpty()) {
                $recentActivity = Image::with('user')
                    ->whereIn('user_id', $followedIds)
                    ->orderByDesc('created_at')
                    ->get()
                    ->unique('user_id')
                    ->take(5)
                    ->values();
            }
        }

        /* Respuesta AJAX y carga de la vista
           --------------------------------------------------------- */

        if ($request->ajax()) {
            return response()->json([
                'images' => $images->items(),
                'next_page' => $images->nextPageUrl(),
            ]);
        }

        View::share('images', $images);
        View::share('recentActivity', $recentActivity);

        return view('dashboard', compact('images', 'recentActivity'));
    }

    /* =========================================================
       DETALLE DE UNA PUBLICACIÓN
       ========================================================= */

    public function unaimagen(Request $request, $id)
    {
        $image = Image::with(['user'])
            ->withCount('likes')
            ->findOrFail($id);

        $comments = Comment::with('user')
            ->where('image_id', $id)
            ->latest()
            ->paginate(10);

        Carbon::setLocale('es');

        // Añade el tiempo relativo en español a cada comentario.
        $comments->getCollection()->transform(function ($comment) {
            $comment->time_ago = $comment->created_at->diffForHumans();

            return $comment;
        });

        if ($request->ajax()) {
            return response()->json([
                'comments' => $comments->items(),
                'next_page' => $comments->nextPageUrl(),
            ]);
        }

        $userId = auth()->id();

        // Determina si la publicación está marcada con Me gusta por el usuario actual.
        if ($userId) {
            $image->has_liked = Like::where('user_id', $userId)
                ->where('image_id', $image->id)
                ->exists();
        } else {
            $image->has_liked = false;
        }

        return view('app.imagen', compact('image', 'comments'));
    }

    /* =========================================================
       SUBIDA DE IMÁGENES
       ========================================================= */

    /* Validación y recepción de versiones
       --------------------------------------------------------- */

    public function store(Request $request)
    {
        /*
         * Se reciben tres versiones de la misma imagen:
         * - image_original: archivo original seleccionado por el usuario.
         * - image_2k: mismo recorte, hasta 2048 px de ancho, JPEG 95.
         * - image_feed: mismo recorte, hasta 1280 px de ancho, JPEG 85.
         */
        $request->validate([
            'image_original' => 'required|image|mimes:jpeg,jpg,png,webp|max:51200',
            'image_2k' => 'required|image|mimes:jpeg,jpg|max:10240',
            'image_feed' => 'required|image|mimes:jpeg,jpg|max:5120',
            'description' => 'nullable|string|max:10240',
        ], [
            'image_original.max' => 'La imagen original es demasiado grande. El tamaño máximo permitido es de 50 MB.',

            'image_2k.max' => 'La versión 2K de la imagen es demasiado grande.',

            'image_feed.max' => 'La versión para el feed es demasiado grande.',

            'image_original.image' => 'El archivo seleccionado debe ser una imagen.',

            'image_2k.image' => 'No se ha podido generar correctamente la versión 2K.',

            'image_feed.image' => 'No se ha podido generar correctamente la versión para el feed.',
        ]);

        if (
            $request->hasFile('image_original') &&
            $request->hasFile('image_2k') &&
            $request->hasFile('image_feed')
        ) {
            $fileOriginal = $request->file('image_original');
            $file2K = $request->file('image_2k');
            $fileFeed = $request->file('image_feed');

            /* Nombres de archivo
               --------------------------------------------------------- */

            // Un único identificador relaciona las tres versiones almacenadas.
            $nombreBase = uniqid().'_'.time();

            // El original conserva su extensión real y no se recomprime.
            $extensionOriginal = strtolower(
                $fileOriginal->getClientOriginalExtension() ?: 'jpg'
            );

            if ($extensionOriginal === 'jpeg') {
                $extensionOriginal = 'jpg';
            }

            $nombreOriginal =
                $nombreBase.'.'.$extensionOriginal;

            /*
             * Las versiones 2K y feed utilizan JPEG.
             * image_path sigue apuntando a la versión 2K para conservar
             * el funcionamiento actual de las vistas.
             */
            $nombreArchivo = $nombreBase.'.jpg';

            /* Almacenamiento de las tres versiones
               --------------------------------------------------------- */

            // Original: storage/app/public/images/original/{nombre}.{extension}
            $fileOriginal->storeAs(
                'images/original',
                $nombreOriginal,
                'public'
            );

            /*
             * 2K: storage/app/public/images/{nombre}.jpg
             * Se mantiene en /images para conservar la ruta usada por el detalle.
             */
            $file2K->storeAs(
                'images',
                $nombreArchivo,
                'public'
            );

            // Feed: storage/app/public/images/feed/{nombre}.jpg
            $fileFeed->storeAs(
                'images/feed',
                $nombreArchivo,
                'public'
            );

            Image::create([
                'image_path' => $nombreArchivo,
                'description' => $request->input('description'),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => '¡Imagen guardada en original, 2K y feed!',
                'image_name' => $nombreArchivo,
                'original_name' => $nombreOriginal,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No se recibieron las tres versiones de la imagen',
        ], 400);
    }

    /* =========================================================
       ELIMINACIÓN DE IMÁGENES
       ========================================================= */

    public function delete(Request $request, $id)
    {
        $image = Image::findOrFail($id);

        // Las versiones 2K y feed comparten el nombre guardado en image_path.
        $rutas = [
            'images/'.$image->image_path,
            'images/feed/'.$image->image_path,
        ];

        /*
         * El original puede conservar jpg, png o webp. Como su extensión
         * no se guarda en otra columna, se localiza mediante el mismo nombre base.
         */
        $nombreBase =
            pathinfo(
                $image->image_path,
                PATHINFO_FILENAME
            );

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
            $rutaOriginal =
                'images/original/'.
                $nombreBase.
                '.'.
                $extension;

            if (Storage::disk('public')->exists($rutaOriginal)) {
                $rutas[] = $rutaOriginal;
            }
        }

        Storage::disk('public')->delete($rutas);

        $image->delete();

        return redirect('u/'.auth()->user()->nick)
            ->with('success', '¡Imagen borrada correctamente!');
    }

    /* =========================================================
       EDICIÓN DE LA DESCRIPCIÓN
       ========================================================= */

    public function update(Request $request, $id)
    {
        $image = Image::findOrFail($id);

        // Normaliza los saltos de línea antes de validar la descripción.
        if ($request->has('description')) {
            $request->merge([
                'description' => str_replace("\r\n", "\n", $request->description),
            ]);
        }

        $validator = \Validator::make($request->all(), [
            'description' => 'nullable|string|max:2200',
        ], [
            'description.max' => 'La descripción es demasiado larga, supera los 2200 caracteres.',
        ]);

        if ($validator->fails()) {
            $mensajeError = $validator->errors()->first('description');

            return back()->with('error', $mensajeError);
        }

        $image->update([
            'description' => $request->description,
        ]);

        return back()->with('success', 'Descripción editada correctamente!');
    }
}
