@extends('layouts.app')

@section('title', 'Создание заявления - Нарушениям.Нет')

@section('content')
    <section class="auth-section" aria-labelledby="report-create-title">
        <div class="auth-card auth-card-wide">
            <p class="eyebrow">Новое обращение</p>
            <h1 id="report-create-title">Создание заявления</h1>

            @if (session('success'))
                <div class="success-message" role="status">{{ session('success') }}</div>
            @endif

            <form action="{{ route('reports.store') }}" method="post" class="form-grid two-column-form" data-client-form novalidate>
                @csrf

                <label class="form-field form-field-full">
                    <span>Название проблемы</span>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Например: Яма во дворе" required>
                    @error('title')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="form-field">
                    <span>Категория</span>
                    <select name="category_id" required>
                        <option value="">Выберите категорию</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <label class="form-field">
                    <span>Дата нарушения</span>
                    <input type="date" name="date_incident" value="{{ old('date_incident', $currentDate) }}" required>
                    @error('date_incident')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <fieldset class="form-field form-field-full radio-group">
                    <legend>Способ обратной связи</legend>
                    <label class="radio-option">
                        <input type="radio" name="contact" value="email" @checked(old('contact', 'email') === 'email') required>
                        <span>Email</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="contact" value="sms" @checked(old('contact') === 'sms') required>
                        <span>SMS на телефон</span>
                    </label>
                    @error('contact')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </fieldset>

                <label class="form-field form-field-full">
                    <span>Описание</span>
                    <textarea name="description" rows="6" placeholder="Опишите проблему и место нарушения" required>{{ old('description') }}</textarea>
                    @error('description')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </label>

                <button type="submit" class="primary-action form-submit form-field-full">Отправить</button>
            </form>
        </div>
    </section>
@endsection
