<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Нарушениям.Нет'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="container header-grid">
            <a class="brand" href="/" aria-label="На главную страницу">
                <img src="{{ asset('images/logo.png') }}" alt="Логотип Нарушениям.Нет" class="brand-logo">
                <span class="brand-name">Нарушениям.Нет</span>
            </a>

            <nav class="main-nav" aria-label="Основная навигация">
                <a href="/" class="nav-link @if(request()->routeIs('home')) nav-link-active @endif">Главная</a>

                @guest
                    <a href="/login" class="nav-link @if(request()->routeIs('login')) nav-link-active @endif">Вход</a>
                    <a href="/register" class="nav-link nav-link-accent @if(request()->routeIs('register')) nav-link-active @endif">Регистрация</a>
                @else
                    @if (auth()->user()->role === 'admin')
                        <a href="/admin/reports" class="nav-link @if(request()->routeIs('admin.*')) nav-link-active @endif">Админ-панель</a>
                    @else
                        <a href="/reports/create" class="nav-link @if(request()->routeIs('reports.create')) nav-link-active @endif">Создать заявку</a>
                        <a href="/reports" class="nav-link @if(request()->routeIs('reports.index')) nav-link-active @endif">Мои заявки</a>
                    @endif

                    <form action="/logout" method="post" class="logout-form">
                        @csrf
                        <button type="submit" class="nav-link nav-button">Выход</button>
                    </form>
                @endguest
            </nav>
        </div>
    </header>

    <main class="site-main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-text">
            Мы помогаем. На данный момент количество решённых проблем:
            <strong>{{ $resolvedReportsCount ?? 0 }}</strong>
        </div>
    </footer>
</body>
</html>
