<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    /* =========================================================
       MÉTODOS RESOURCE SIN IMPLEMENTACIÓN
       ========================================================= */

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


/* =========================================================
   PERFIL DE USUARIO
   ========================================================= */



/* Galería y carga progresiva
   --------------------------------------------------------- */

public function show(Request $request, User $usuario)
{
    $initialGalleryLimit = 12;
    $loadMoreLimit = 12;

    $imagesQuery = $usuario->images()
        ->withCount(['likes', 'comments'])
        ->orderByDesc('created_at');

    /*
     * La carga adicional usa un offset manual para continuar desde
     * el número de imágenes ya mostrado sin repetir publicaciones.
     */
    if ($request->has('gallery_offset')) {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Debes iniciar sesión para cargar más publicaciones.',
                'login_url' => route('login'),
            ], 401);
        }

        $offset = max(
            $initialGalleryLimit,
            (int) $request->query('gallery_offset', $initialGalleryLimit)
        );

        // Solicita una imagen adicional para determinar si quedan más resultados.
        $batch = (clone $imagesQuery)
            ->skip($offset)
            ->take($loadMoreLimit + 1)
            ->get();

        $hasMore = $batch->count() > $loadMoreLimit;
        $images = $batch->take($loadMoreLimit)->values();



        return response()->json([
            'images' => $images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'image_url' => asset('storage/images/' . $image->image_path),
                    'detail_url' => route('unaimagen', ['id' => $image->id]),
                    'likes_count' => $image->likes_count ?? 0,
                    'comments_count' => $image->comments_count ?? 0,
                ];
            })->values(),

            'next_offset' => $hasMore
                ? $offset + $loadMoreLimit
                : null,

            'has_more' => $hasMore,
        ]);
    }

    // Primera carga de la galería del perfil.
    $profileImages = (clone $imagesQuery)
        ->take($initialGalleryLimit)
        ->get();



    $userId = auth()->id();

    /* Actividad reciente
       --------------------------------------------------------- */

    $recentActivity = collect();

    if ($userId) {
        // Obtiene los usuarios que sigue la cuenta autenticada.
        $followedIds = DB::table('followers')
            ->where('follower_id', $userId)
            ->pluck('followed_id');

        if ($followedIds->isNotEmpty()) {
            // Localiza la publicación más reciente de cada usuario seguido.
            $latestImageIds = DB::table('images')
                ->selectRaw('MAX(id)')
                ->whereIn('user_id', $followedIds)
                ->groupBy('user_id');

            $recentActivity = Image::with('user')
                ->whereIn('id', $latestImageIds)
                ->latest()
                ->take(5)
                ->get();
        }
    }

    /* Contadores y datos compartidos
       --------------------------------------------------------- */

    // Calcula los contadores reales del resto de perfiles.
    $usuario->loadCount([
        'images',
        'followers',
        'likes',
        'likesRecibidos',
        'comments',
    ]);


    $hasMoreImages = $usuario->images_count > $profileImages->count();
    $nextGalleryOffset = $hasMoreImages
        ? $profileImages->count()
        : null;

    // Mantiene disponibles las variables compartidas utilizadas por las vistas.
    View::share('images', $profileImages);
    View::share('recentActivity', $recentActivity);

    return view('app.usuario', compact(
        'usuario',
        'recentActivity',
        'profileImages',
        'hasMoreImages',
        'nextGalleryOffset'
    ));
}




    /* =========================================================
       BUSCADOR DE USUARIOS
       ========================================================= */

    public function buscador($search)
    {
        if (!empty($search)) {
            $usuarios = User::where(function ($query) use ($search) {
                // Agrupa nombre y nick dentro de una misma condición de búsqueda.
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('nick', 'LIKE', '%' . $search . '%');
            })
                // Prioridad máxima: coincidencia exacta de nombre o nick.
                ->orderByRaw("name = ? DESC", [$search])
                ->orderByRaw("nick = ? DESC", [$search])

                // Prioridad media: nombre o nick que comienzan por el término.
                ->orderByRaw("name LIKE ? DESC", [$search . '%'])
                ->orderByRaw("nick LIKE ? DESC", [$search . '%'])

                // Prioridad baja: coincidencias parciales, ordenadas por nombre.
                ->orderBy('name', 'ASC')
                ->take(5)
                ->get();
        } else {
            $usuarios = User::orderBy('id', 'desc')
                ->paginate(5);
        }

        return response()->json($usuarios);
    }


    /* =========================================================
       DESCRIPCIÓN DEL PERFIL
       ========================================================= */

    public function updateDescription(Request $request)
    {
        $request->validate([
            'description' => ['nullable', 'string', 'max:650'],
        ]);

        $request->user()->forceFill([
            'description' => $request->description,
        ])->save();

        return back()->with('success', '¡Descripción actualizada correctamente!');
    }


    /* =========================================================
       MÉTODOS RESOURCE SIN IMPLEMENTACIÓN
       ========================================================= */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}