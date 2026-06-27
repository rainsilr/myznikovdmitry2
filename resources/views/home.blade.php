@extends('layouts.app')

@section('title', 'Нарушениям.Нет - городские проблемы')

@section('content')
    <section class="hero-section" aria-labelledby="home-title">
        <div class="container hero-grid">
            <div class="hero-content">
                <p class="eyebrow">Городской сервис обращений</p>
                <h1 id="home-title">Сообщайте о проблемах и следите за их решением</h1>
                <p class="hero-text">
                    Сайт помогает жителям отправлять заявления о нарушениях, выбирать категорию проблемы
                    и видеть актуальный статус обработки.
                </p>
                <a href="/reports/create" class="primary-action">Отправить заявление</a>
            </div>

            <section class="slider" aria-label="Фотографии городских проблем">
                <div class="slider-track" data-slider-track>
                    <article class="slide is-active">
                        <img src="{{ asset('images/slider-1.jpg') }}" alt="Городская проблема на дороге">
                    </article>
                    <article class="slide">
                        <img src="{{ asset('images/slider-2.jpg') }}" alt="Проблема городской инфраструктуры">
                    </article>
                    <article class="slide">
                        <img src="{{ asset('images/slider-3.jpg') }}" alt="Нарушение благоустройства">
                    </article>
                </div>

                <button class="slider-button slider-button-prev" type="button" data-slider-prev aria-label="Предыдущий слайд">
                    ‹
                </button>
                <button class="slider-button slider-button-next" type="button" data-slider-next aria-label="Следующий слайд">
                    ›
                </button>
            </section>
        </div>
    </section>
@endsection
