@extends('layouts.main')

@section('content')
<div class="acceso">
    <h1 class="acceso__titulo">Crear cuenta</h1>
    <p class="acceso__subtitulo">Introduce tus datos para registrarte</p>

    @if (session('status'))
        <div class="acceso__estado">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="acceso__formulario">
        @csrf

        <div class="campo">
            <label class="campo__etiqueta" for="name">Nombre completo</label>
            <input id="name" class="campo__entrada" type="text" name="name" value="{{ old('name') }}"
                   required autofocus autocomplete="name" placeholder="Tu nombre">
            @error('name')
                <span class="campo__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label class="campo__etiqueta" for="email">Correo electrónico</label>
            <input id="email" class="campo__entrada" type="email" name="email" value="{{ old('email') }}"
                   required autocomplete="email" placeholder="email@ejemplo.com">
            @error('email')
                <span class="campo__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label class="campo__etiqueta" for="password">Contraseña</label>
            <input id="password" class="campo__entrada" type="password" name="password"
                   required autocomplete="new-password" placeholder="Tu contraseña">
            @error('password')
                <span class="campo__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label class="campo__etiqueta" for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" class="campo__entrada" type="password" name="password_confirmation"
                   required autocomplete="new-password" placeholder="Repite tu contraseña">
            @error('password_confirmation')
                <span class="campo__error">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="boton-primario">Crear cuenta</button>
    </form>

    @if (Route::has('login'))
        <div class="acceso__pie">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
        </div>
    @endif
</div>
@endsection