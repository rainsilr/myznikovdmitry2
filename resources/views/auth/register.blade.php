@extends('layouts.app')

@section('title', 'Регистрация - Нарушениям.Нет')

@section('content')
    <section class="auth-section" aria-labelledby="register-title">
        <div class="auth-card auth-card-wide">
            <p class="eyebrow">Создание профиля</p>
            <h1 id="register-title">Регистрация</h1>

            <form action="{{ route('register') }}" method="post" class="form-grid two-column-form" data-client-form novalidate>
                @csrf

                <label class="form-field form-field-full">
                    <span>ФИО</span>
                    <input
                        type="text"
                        name="fio"
                        value="{{ old('fio') }}"
                        placeholder="Иванов Иван Иванович"
                        data-pattern="fio"
                        required
                    >
                    @error('fio')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="form-field">
                    <span>Логин</span>
                    <input type="text" name="login" value="{{ old('login') }}" placeholder="user123" minlength="4" data-pattern="login" required>
                    @error('login')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="form-field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="user@example.ru" required>
                    @error('email')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="form-field">
                    <span>Пароль</span>
                    <input type="password" name="password" placeholder="Минимум 6 символов" minlength="6" required>
                    @error('password')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="form-field">
                    <span>Телефон</span>
                    <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+7(999)123-45-67" data-pattern="phone" required>
                    @error('phone')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <button type="submit" class="primary-action form-submit form-field-full">Зарегистрироваться</button>
            </form>

            <p class="form-link-text">
                Есть профиль?
                <a href="{{ route('login') }}">Авторизоваться.</a>
            </p>
        </div>
    </section>
@endsection
