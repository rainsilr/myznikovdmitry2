<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Показывает форму создания заявления для авторизованного пользователя.
     */
    public function create(): View
    {
        return view('reports.create', [
            'categories' => Category::orderBy('name')->get(),
            'currentDate' => now()->format('d.m.Y'),
            'resolvedReportsCount' => $this->resolvedReportsCount(),
        ]);
    }

    /**
     * Проверяет данные формы и сохраняет новое заявление в базе.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'date_incident' => ['required', 'date_format:d.m.Y'],
            'contact' => ['required', 'in:email,sms'],
        ], [
            'category_id.exists' => 'Выберите категорию из списка.',
            'date_incident.date_format' => 'Дата должна быть в формате ДД.ММ.ГГГГ.',
            'contact.in' => 'Выберите способ обратной связи.',
        ]);

        // Новое заявление всегда создается со статусом new по требованиям задания.
        Report::create([
            'user_id' => $request->user()->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date_incident' => Carbon::createFromFormat('d.m.Y', $validated['date_incident'])->format('Y-m-d'),
            'contact' => $validated['contact'],
            'status' => 'new',
        ]);

        return redirect()
            ->route('reports.create')
            ->with('success', 'Заявление отправлено.');
    }

    /**
     * Возвращает число решенных заявлений для общего подвала.
     */
    private function resolvedReportsCount(): int
    {
        return Report::where('status', 'resolved')->count();
    }
}
