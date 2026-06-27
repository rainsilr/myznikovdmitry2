@extends('layouts.app')

@section('title', 'Доступ запрещен - Нарушениям.Нет')

@section('content')
    <section class="auth-section" aria-labelledby="error-title">
        <div class="auth-card error-card">
            <p class="eyebrow">Ошибка 403</p>
            <h1 id="error-title">Доступ запрещен</h1>
            <p class="error-text">
                Эта страница доступна только пользователю с правами администратора.
            </p>
            <a href="{{ route('home') }}" class="primary-action">На главную</a>
        </div>
    </section>
@endsection
