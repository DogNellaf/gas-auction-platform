<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <title>@yield('title', 'Газовый аукцион')</title>
</head>
<body>
    <header class="navbar navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <a href="{{ route('index') }}" class="navbar-brand me-auto">Газовый аукцион</a>
            @auth
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('admin.index') }}" class="nav-item nav-link">Панель администратора</a>
                @else
                    <a href="{{ route('home') }}" class="nav-item nav-link">Мои заявки</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="nav-item nav-link">Вход</a>
            @endauth
        </div>
    </header>

    <div class="container my-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="navbar fixed-bottom navbar-light bg-light text-center">
        <p class="footer-text">Газовый аукцион &copy; {{ date('Y') }}</p>
    </footer>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
