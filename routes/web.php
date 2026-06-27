<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Models\Category;
use App\Models\Report;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Счетчик решенных заявлений выводится в подвале на каждой странице.
    $resolvedReportsCount = Report::where('status', 'resolved')->count();
    $reportsCount = Report::count();
    $categories = Category::orderBy('name')->get();

    return view('home', compact('resolvedReportsCount', 'reportsCount', 'categories'));
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::post('/reports/{report}/feedback', [ReportController::class, 'feedback'])->name('reports.feedback');

    Route::get('/admin/reports', [AdminController::class, 'index'])->name('admin.reports.index');
    Route::patch('/admin/reports/{report}/status', [AdminController::class, 'updateStatus'])->name('admin.reports.status');
});
