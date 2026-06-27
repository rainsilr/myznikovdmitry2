@extends('layouts.app')

@section('title', 'Мои заявки - Нарушениям.Нет')

@section('content')
    <section class="page-section" aria-labelledby="reports-title">
        <div class="container">
            <div class="page-heading">
                <p class="eyebrow">История обращений</p>
                <h1 id="reports-title">Мои заявки</h1>
            </div>

            @if (session('success'))
                <div class="success-message" role="status">{{ session('success') }}</div>
            @endif

            @forelse ($reports as $report)
                <article class="report-item">
                    <div class="report-main">
                        <div class="report-meta">
                            <time datetime="{{ $report->date_incident }}">
                                {{ \Illuminate\Support\Carbon::parse($report->date_incident)->format('d.m.Y') }}
                            </time>
                            <span>{{ $report->category->name }}</span>
                        </div>

                        <h2>{{ $report->title }}</h2>

                        <span class="status-badge status-{{ $report->status }}">
                            {{ $report->statusLabel() }}
                        </span>
                    </div>

                    @if ($report->status === 'resolved')
                        <form action="{{ route('reports.feedback', $report) }}" method="post" class="feedback-form" data-client-form novalidate>
                            @csrf
                            <label class="form-field">
                                <span>Отзыв о качестве работ</span>
                                <textarea name="feedback" rows="3" placeholder="Оставьте отзыв" required>{{ old('feedback', $report->feedback) }}</textarea>
                                @error('feedback')
                                    <small class="field-error">{{ $message }}</small>
                                @enderror
                            </label>
                            <button type="submit" class="secondary-action">Сохранить отзыв</button>
                        </form>
                    @elseif ($report->feedback)
                        <p class="feedback-text">{{ $report->feedback }}</p>
                    @endif
                </article>
            @empty
                <div class="empty-state">
                    <h2>Заявок пока нет</h2>
                    <p>Создайте первое заявление, чтобы отслеживать ход решения проблемы.</p>
                    <a href="{{ route('reports.create') }}" class="primary-action">Создать заявку</a>
                </div>
            @endforelse
        </div>
    </section>
@endsection
