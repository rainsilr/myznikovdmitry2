<?php

use Illuminate\Support\Facades\Route;
use App\Models\Report;

Route::get('/', function () {
    // Счетчик решенных заявлений выводится в подвале на каждой странице.
    $resolvedReportsCount = Report::where('status', 'resolved')->count();

    return view('home', compact('resolvedReportsCount'));
})->name('home');
