<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetPin | Galería animal</title>
    @vite('resources/css/layout.css')
</head>

<body>
    <nav class="barra-navegacion">
        <div class="nav-enlaces">
            <a href="{{ route('home') }}" class="nav-marca">PetPin</a>
            <a class="nav-enlace {{ request()->routeIs('home') ? 'activo' : '' }}" href="{{ route('home') }}">Inicio</a>
            <a class="nav-enlace {{ request()->routeIs('search') ? 'activo' : '' }}" href="{{ route('search') }}">Buscar</a>
        </div>

        <div class="nav-botones">
            <a class="boton-subir" href="{{ route('upload') }}">+ Upload</a>
            @guest
                <a class="boton-acceso" href="{{ route('login') }}">Login</a>
            @else
                <form method="post" action="{{ route('logout') }}" class="boton-acceso">
                    @csrf
                    <button class="cerrar-sesion" type="submit">Logout</button>
                </form>
            @endguest
        </div>
    </nav>

    @yield('content')
</body>

</html>
