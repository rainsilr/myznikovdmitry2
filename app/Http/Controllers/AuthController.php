<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Показывает форму входа для неавторизованных посетителей.
     */
    public function showLogin(): View
    {
        return view('auth.login', [
            'resolvedReportsCount' => $this->resolvedReportsCount(),
        ]);
    }

    /**
     * Проверяет логин и пароль, затем создает пользовательскую сессию.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Неверный логин или пароль']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    /**
     * Показывает форму регистрации нового пользователя.
     */
    public function showRegister(): View
    {
        return view('auth.register', [
            'resolvedReportsCount' => $this->resolvedReportsCount(),
        ]);
    }

    /**
     * Создает пользователя с ролью user и сразу авторизует его.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fio' => [
                'required',
                'string',
                'regex:/^[А-ЯЁа-яё]+(?:-[А-ЯЁа-яё]+)?\s+[А-ЯЁа-яё]+(?:-[А-ЯЁа-яё]+)?\s+[А-ЯЁа-яё]+(?:-[А-ЯЁа-яё]+)?$/u',
            ],
            'login' => ['required', 'string', 'min:4', 'regex:/^[A-Za-z0-9]+$/', 'unique:users,login'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(6)],
            'phone' => ['required', 'regex:/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/'],
        ], [
            'fio.regex' => 'ФИО должно состоять ровно из 3 слов кириллицей. Допускаются пробелы и дефисы.',
            'login.regex' => 'Логин может содержать только латиницу и цифры.',
            'phone.regex' => 'Телефон должен быть в формате +7(XXX)XXX-XX-XX.',
            'unique' => 'Такое значение уже используется.',
        ]);

        // Новые зарегистрированные пользователи получают обычную роль user.
        $user = User::create([
            'fio' => $validated['fio'],
            'login' => $validated['login'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'],
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    /**
     * Завершает сессию текущего пользователя.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Возвращает число решенных заявлений для общего подвала.
     */
    private function resolvedReportsCount(): int
    {
        return Report::where('status', 'resolved')->count();
    }
}
