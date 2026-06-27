<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Показывает все заявления пользователей с фильтром и пагинацией.
     */
    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $status = $request->query('status');
        $reports = Report::query()
            ->with(['category', 'user'])
            ->when(in_array($status, ['new', 'resolved', 'rejected'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('admin.reports', [
            'reports' => $reports,
            'currentStatus' => $status,
            'resolvedReportsCount' => $this->resolvedReportsCount(),
        ]);
    }

    /**
     * Меняет статус заявления после подтверждения действия в интерфейсе.
     */
    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'status' => ['required', 'in:new,resolved,rejected'],
        ]);

        $report->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.reports.index')
            ->with('success', 'Статус заявки обновлен.');
    }

    /**
     * Проверяет, что текущий пользователь является администратором.
     */
    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'admin', 403);
    }

    /**
     * Возвращает число решенных заявлений для общего подвала.
     */
    private function resolvedReportsCount(): int
    {
        return Report::where('status', 'resolved')->count();
    }
}
