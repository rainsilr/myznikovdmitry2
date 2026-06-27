@extends('layouts.app')

@section('title', 'Авторизация - Нарушениям.Нет')

@section('content')
    <section class="auth-section" aria-labelledby="login-title">
        <div class="auth-card">
            <p class="eyebrow">Вход в профиль</p>
            <h1 id="login-title">Авторизация</h1>

            <form action="{{ route('login') }}" method="post" class="form-grid" data-client-form novalidate>
                @csrf

                <label class="form-field">
                    <span>Логин</span>
                    <input type="text" name="login" value="{{ old('login') }}" placeholder="Введите логин" required>
                    @error('login')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="form-field">
                    <span>Пароль</span>
                    <input type="password" name="password" placeholder="Введите пароль" required>
                    @error('password')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <button type="submit" class="primary-action form-submit">Авторизация</button>
            </form>

            <p class="form-link-text">
                Нет профиля?
                <a href="{{ route('register') }}">Зарегистрироваться.</a>
            </p>
        </div>
    </section>
@endsection
