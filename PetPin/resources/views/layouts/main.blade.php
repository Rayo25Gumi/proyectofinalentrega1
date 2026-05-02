<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetiPin!</title>
    @vite('resources/css/layout.css')
</head>

<body>
    <nav class="bar">
        <div class="nav-links">
            <a href="/" class="nav-logo">PetPin!</a>
            <a class="nav-link" href="{{ route('home') }}">Inicio</a>
            <a class="nav-link" href="{{ route('search') }}">Buscar</a>
        </div>

        <div class="nav-buttons">
            <a class="button-upload" href="{{ route('upload') }}">+ Upload</a>
            @guest
            <a class="button-login" href="{{ route('login') }}">Login</a>
            @else
            <form class="button-login" method="post" action="{{ route('logout') }}">
                @csrf
                <button class="logout" type="submit">Logout</a>
            </form>
            @endguest

        </div>
    </nav>

    @yield('content')
</body>

</html>