{{-- =========================================================
     AVATAR DEL USUARIO
     ========================================================= --}}

@props([
    'user',
    'class' => '',
    'size' => 'small',
])

@php
    $esGrande = $size === 'large';
@endphp

<div {{ $attributes->merge(['class' => 'user-avatar ' . $class]) }}>
    @if ($user->profile_photo_path)
        @php
            $photoUrl = $esGrande
                ? $user->profile_photo_url
                : asset('storage/profile-photos/small/' . basename($user->profile_photo_path));
        @endphp

        <img src="{{ $photoUrl }}" alt="Avatar de {{ $user->name }}">
    @else
        @php
            $textoBase = trim($user->name ?? $user->nick ?? 'U');
            $letra = mb_strtoupper(mb_substr($textoBase, 0, 1));

            $carpetaAvatar = $esGrande
                ? 'avatar-letters'
                : 'avatar-letters-small';
        @endphp

        <img
            class="img-letter"
            src="{{ asset("images/{$carpetaAvatar}/{$letra}.webp") }}"
            alt="Avatar de {{ $textoBase }}"
        >
    @endif
</div> 