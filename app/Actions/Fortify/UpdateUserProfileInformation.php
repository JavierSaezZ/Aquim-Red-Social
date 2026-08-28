<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Support\ProfilePhotoOptimizer;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        /*
         * La contraseña solo será obligatoria si cambia
         * el nick o el correo electrónico.
         *
         * El nombre y la foto se pueden modificar
         * sin volver a introducir la contraseña.
         */
        $requiresPassword =
            ($input['nick'] ?? $user->nick) !== $user->nick ||
            ($input['email'] ?? $user->email) !== $user->email;

        Validator::make($input, [
'name' => [
    'required',
    'string',
    'max:64',
    'regex:/^[A-Za-zÑñ ]+$/u',
],

            'nick' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'nick')->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],

            'current_password' => [
                Rule::requiredIf($requiresPassword),
                'nullable',
                'current_password',
            ],

            'photo' => [
                'nullable',
                'mimes:jpg,jpeg,png,webp',
                'max:1024',
            ],

        ], [
            'name.regex' => 'El nombre no puede contener números ni caracteres especiales.',
            'nick.required' => 'El nick es obligatorio.',
            'nick.max' => 'El nick no puede tener más de 30 caracteres.',
            'nick.unique' => 'Este nick ya está siendo utilizado.',
            'current_password.required' => 'Introduce tu contraseña para guardar los cambios.',
            'current_password.current_password' => 'La contraseña introducida no es correcta.',
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {
            $oldProfilePhotoPath = $user->profile_photo_path;

            $user->updateProfilePhoto($input['photo']);
            $user->refresh();

            if ($oldProfilePhotoPath && $oldProfilePhotoPath !== $user->profile_photo_path) {
                ProfilePhotoOptimizer::deleteSmall($oldProfilePhotoPath);
            }

            if ($user->profile_photo_path) {
                ProfilePhotoOptimizer::createSmall($user->profile_photo_path);
            }
        }

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'name' => $input['name'],
                'nick' => $input['nick'],
                'email' => $input['email'],
            ])->save();
        }

        if (isset($input['photo'])) {
            session()->flash('success', 'profile.updated');
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'name' => $input['name'],
            'nick' => $input['nick'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}