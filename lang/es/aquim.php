<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */

    'profile' => [
        'updated' => 'Perfil actualizado correctamente.',
        'photo_deleted' => 'Foto de perfil eliminada correctamente.',
        'photo_error' => 'No se ha podido cargar la foto de perfil.',
    ],


    /*
    |--------------------------------------------------------------------------
    | CONTRASEÑA
    |--------------------------------------------------------------------------
    */

    'password' => [
        'updated' => 'Contraseña actualizada correctamente.',
    ],


    /*
    |--------------------------------------------------------------------------
    | SESIONES
    |--------------------------------------------------------------------------
    */

    'sessions' => [
        'closed' => 'Se han cerrado las otras sesiones correctamente.',
    ],


    /*
    |--------------------------------------------------------------------------
    | AUTENTICACIÓN EN DOS PASOS
    |--------------------------------------------------------------------------
    */

    '2fa' => [
        'enabled' => 'Autenticación en dos pasos activada correctamente.',
        'disabled' => 'Autenticación en dos pasos desactivada correctamente.',
        'cancelled' => 'Activación de la autenticación en dos pasos cancelada.',
        'recovery_regenerated' => 'Códigos de recuperación regenerados correctamente.',
    ],


    /*
    |--------------------------------------------------------------------------
    | MENSAJES LITERALES DE LARAVEL / JETSTREAM / FORTIFY
    |--------------------------------------------------------------------------
    |
    | Respaldo para mensajes que algún componente entregue literalmente
    | en inglés en lugar de pasar por auth.php / validation.php.
    |
    */

    'vendor' => [

        'This password does not match our records.' =>
            'La contraseña no es correcta.',

        'The provided password does not match your current password.' =>
            'La contraseña actual no es correcta.',

        'The provided password was incorrect.' =>
            'La contraseña no es correcta.',

        'The current password field is required.' =>
            'Debes introducir tu contraseña actual.',

        'The password field is required.' =>
            'Debes introducir una contraseña.',

        'The password confirmation field is required.' =>
            'Debes confirmar la contraseña.',

        'The password field must be at least 8 characters.' =>
            'La contraseña debe tener al menos 8 caracteres.',

        'The password field confirmation does not match.' =>
            'Las contraseñas no coinciden.',

        'The password confirmation does not match.' =>
            'Las contraseñas no coinciden.',

        'The new password must be different from the current password.' =>
            'La nueva contraseña debe ser diferente de la contraseña actual.',

        'The provided two factor authentication code was invalid.' =>
            'El código de autenticación en dos pasos no es válido.',

        'The code field is required.' =>
            'Debes introducir el código de autenticación.',

        'The name field is required.' =>
            'El nombre es obligatorio.',

        'The name must be a string.' =>
            'El nombre no es válido.',

        'The name field must not be greater than 80 characters.' =>
            'El nombre no puede tener más de 80 caracteres.',

        'The name may not be greater than 80 characters.' =>
            'El nombre no puede tener más de 80 caracteres.',

        'The nick field is required.' =>
            'El nick es obligatorio.',

        'The nick has already been taken.' =>
            'Ese nick ya está siendo utilizado.',

        'The nick must be a string.' =>
            'El nick no es válido.',

        'The nick field must not be greater than 30 characters.' =>
            'El nick no puede tener más de 30 caracteres.',

        'The nick may not be greater than 30 characters.' =>
            'El nick no puede tener más de 30 caracteres.',

        'The email field is required.' =>
            'El correo electrónico es obligatorio.',

        'The email must be a valid email address.' =>
            'Introduce una dirección de correo electrónico válida.',

        'The email field must be a valid email address.' =>
            'Introduce una dirección de correo electrónico válida.',

        'The email has already been taken.' =>
            'Ese correo electrónico ya está siendo utilizado.',

        'The photo failed to upload.' =>
            'No se ha podido subir la foto.',

        'The photo must be a file.' =>
            'La foto seleccionada no es válida.',

        'The photo must be a file of type: jpg, jpeg, png, webp.' =>
            'La foto debe ser JPG, JPEG, PNG o WebP.',

        'The photo field must not be greater than 1024 kilobytes.' =>
            'La foto no puede superar 1 MB.',

        'The photo may not be greater than 1024 kilobytes.' =>
            'La foto no puede superar 1 MB.',
    ],

];