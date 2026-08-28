<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [ImageController::class, 'imagenesIndex'])
        ->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Imágenes
|--------------------------------------------------------------------------
*/

Route::prefix('images')->group(function () {

    // Rutas públicas: cualquiera puede ver el feed y una publicación.
    Route::get('/index', [ImageController::class, 'imagenesIndex'])
        ->name('index');

    /*
     * Acciones que modifican datos.
     * Requieren que el usuario haya iniciado sesión.
     *
     * IMPORTANTE:
     * auth impide el acceso a invitados, pero update/delete también deben
     * comprobar en el controlador que auth()->id() === $image->user_id.
     */
    Route::middleware('auth')->group(function () {

        Route::post('/upload', [ImageController::class, 'store'])
            ->name('images.store');

        Route::put('/updateimages/{id}', [ImageController::class, 'update'])
            ->whereNumber('id')
            ->name('publicaciones.update');

        Route::delete('/destroyimage/{id}', [ImageController::class, 'delete'])
            ->whereNumber('id')
            ->name('publicaciones.destroy');
    });

    // Debe ir después de /index para que "index" no sea interpretado como un ID.
    Route::get('/{id}', [ImageController::class, 'unaimagen'])
        ->whereNumber('id')
        ->name('unaimagen');
});

/*
|--------------------------------------------------------------------------
| Comentarios
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Mantiene la URL actual para no romper formularios o JavaScript existentes.
    Route::post('/images/imagen/{id}/comentar', [CommentController::class, 'store'])
        ->whereNumber('id')
        ->name('comentar.guardar');

    Route::delete('/comments/destroycomment/{id}', [CommentController::class, 'delete'])
        ->whereNumber('id')
        ->name('comentarios.destroy');
});

/*
|--------------------------------------------------------------------------
| Likes
|--------------------------------------------------------------------------
*/

Route::prefix('likes')
    ->middleware('auth')
    ->group(function () {
        Route::post('/{iduser}/{idimage}', [LikeController::class, 'likesuma'])
            ->whereNumber('iduser')
            ->whereNumber('idimage');
    });

/*
|--------------------------------------------------------------------------
| Usuarios
|--------------------------------------------------------------------------
*/

Route::prefix('u')->group(function () {

    // Modifica información del usuario autenticado.
    Route::patch('/description', [UserController::class, 'updateDescription'])
        ->middleware('auth')
        ->name('usuario.description.update');

    // El buscador no modifica datos, por lo que puede seguir siendo público.
    Route::post('/buscador/{search}', [UserController::class, 'buscador'])
        ->where('search', '.+')
        ->name('buscador');

    // Seguir/dejar de seguir modifica datos.
    Route::post('/follow/{folowerid}/{followedid}', [FollowerController::class, 'followed'])
        ->middleware('auth')
        ->whereNumber('folowerid')
        ->whereNumber('followedid')
        ->name('followed');

    // Perfil público. Mantener SIEMPRE al final del grupo para no capturar
    // rutas como /description, /buscador/... o /follow/...
    Route::get('/{usuario}', [UserController::class, 'show'])
        ->name('usuario');
});

/*
|--------------------------------------------------------------------------
| Rutas auxiliares
|--------------------------------------------------------------------------
*/



/*
 * Estas rutas parecen de desarrollo/depuración.
 * No deben quedar expuestas públicamente en producción.
 */
if (app()->environment('local')) {

    Route::get('/limite', function () {
        return 'El límite real que está viendo Laravel es: '.ini_get('post_max_size');
    });

    Route::get('/imagen', function () {
        return view('imagen');
    });
} 
