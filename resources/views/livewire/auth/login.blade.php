@extends('layouts.main')

@section('content')
<div class="acceso">
    <h1 class="acceso__titulo">Inicia sesión</h1>
    <p class="acceso__subtitulo">Introduce tu correo y contraseña</p>

    @if (session('status'))
        <div class="acceso__estado">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="acceso__formulario">
        @csrf

        <div class="campo">
            <label class="campo__etiqueta" for="email">Correo electrónico</label>
            <input id="email" class="campo__entrada" type="email" name="email" value="{{ old('email') }}"
                   required autofocus autocomplete="email" placeholder="email@ejemplo.com">
            @error('email')
                <span class="campo__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label class="campo__etiqueta" for="password">Contraseña</label>
            <input id="password" class="campo__entrada" type="password" name="password"
                   required autocomplete="current-password" placeholder="Tu contraseña">
            @error('password')
                <span class="campo__error">{{ $message }}</span>
            @enderror
        </div>

        <div class="acceso__opciones">
            <label class="acceso__recordar">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                Recordarme
            </label>
            @if (Route::has('password.request'))
                <a class="acceso__olvido" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif
        </div>

        <button type="submit" class="boton-primario">Iniciar sesión</button>
    </form>

    @if (Route::has('register'))
        <div class="acceso__pie">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a>
        </div>
    @endif
</div>
@endsection
