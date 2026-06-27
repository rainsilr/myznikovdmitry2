@extends('layouts.app')

@section('title', 'Админ-панель - Нарушениям.Нет')

@section('content')
    <section class="page-section" aria-labelledby="admin-title">
        <div class="container">
            <div class="page-heading admin-heading">
                <div>
                    <p class="eyebrow">Панель администратора</p>
                    <h1 id="admin-title">Заявки пользователей</h1>
                </div>

                <form action="{{ route('admin.reports.index') }}" method="get" class="filter-form">
                    <label class="form-field">
                        <span>Фильтр по статусу</span>
                        <select name="status" onchange="this.form.submit()">
                            <option value="">Все статусы</option>
                            <option value="new" @selected($currentStatus === 'new')>Новое</option>
                            <option value="resolved" @selected($currentStatus === 'resolved')>Решено</option>
                            <option value="rejected" @selected($currentStatus === 'rejected')>Отклонено</option>
                        </select>
                    </label>
                </form>
            </div>

            @if (session('success'))
                <div class="success-message" role="status">{{ session('success') }}</div>
            @endif

            <div class="admin-report-list admin-report-list-compact">
                @forelse ($reports as $report)
                    <article class="admin-report">
                        <header class="admin-report-header">
                            <div>
                                <span class="report-id">Заявка #{{ $report->id }}</span>
                                <h2>{{ $report->title }}</h2>
                            </div>
                            <span class="status-badge status-{{ $report->status }}">{{ $report->statusLabel() }}</span>
                        </header>

                        <dl class="admin-report-grid">
                            <div>
                                <dt>Категория</dt>
                                <dd>{{ $report->category->name }}</dd>
                            </div>
                            <div>
                                <dt>ФИО заявителя</dt>
                                <dd>{{ $report->user->fio }}</dd>
                            </div>
                            <div>
                                <dt>Способ связи</dt>
                                <dd>
                                    @if ($report->contact === 'sms')
                                        SMS на телефон: {{ $report->user->phone }}
                                    @else
                                        Email: {{ $report->user->email }}
                                    @endif
                                </dd>
                            </div>
                            <div class="admin-report-description">
                                <dt>Описание</dt>
                                <dd>{{ \Illuminate\Support\Str::limit($report->description, 160) }}</dd>
                            </div>
                        </dl>

                        <form
                            action="{{ route('admin.reports.status', $report) }}"
                            method="post"
                            class="status-form"
                            onsubmit="return confirm('Подтвердите смену статуса заявки.');"
                        >
                            @csrf
                            @method('PATCH')

                            <label class="form-field">
                                <span>Новый статус</span>
                                <select name="status" required>
                                    <option value="new" @selected($report->status === 'new')>Новое</option>
                                    <option value="resolved" @selected($report->status === 'resolved')>Решено</option>
                                    <option value="rejected" @selected($report->status === 'rejected')>Отклонено</option>
                                </select>
                            </label>

                            <button type="submit" class="secondary-action">Изменить статус</button>
                        </form>
                    </article>
                @empty
                    <div class="empty-state">
                        <h2>Заявки не найдены</h2>
                        <p>Попробуйте изменить фильтр или дождаться новых обращений.</p>
                    </div>
                @endforelse
            </div>

            <div class="pagination-wrap">
                {{ $reports->links() }}
            </div>
        </div>
    </section>
@endsection
