<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        /*
         * Permite iniciar sesión escribiendo indistintamente:
         * - correo electrónico
         * - nickname
         * - nickname con o sin @
         *
         * Mantenemos config('fortify.username') = 'email' para no alterar
         * el resto del flujo de Fortify. El campo "email" del formulario
         * funciona aquí como identificador de acceso, no exclusivamente email.
         */
        Fortify::authenticateUsing(function (Request $request) {
            $login = trim((string) $request->input('email'));
            $nick = ltrim($login, '@');

            $nickCandidates = array_values(array_unique([
                $login,
                $nick,
                '@' . $nick,
            ]));

            $user = User::query()
                ->where('email', $login)
                ->orWhereIn('nick', $nickCandidates)
                ->first();

            if ($user && Hash::check((string) $request->input('password'), $user->password)) {
                return $user;
            }

            return null;
        });

        Vite::prefetch(concurrency: 3);
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}