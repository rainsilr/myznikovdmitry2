<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            'Ямы на дорогах',
            'Неисправное освещение',
            'Мусор и свалки',
            'Повреждение тротуара',
            'Сломанная детская площадка',
            'Незаконная парковка',
            'Повреждение дорожных знаков',
            'Открытые люки и опасные участки',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }

        $categoryModels = Category::pluck('id', 'name');

        // Учетная запись администратора нужна для входа в панель управления.
        User::updateOrCreate(['login' => 'admin'], [
            'fio' => 'Администратор Сайта Нарушениям',
            'phone' => '+7(000)000-00-00',
            'email' => 'admin@example.ru',
            'password' => Hash::make('adm123'),
            'role' => 'admin',
        ]);

        // Демонстрационные пользователи и заявки помогают сразу проверить админ-панель после развёртывания.
        $demoUsers = [
            [
                'login' => 'ivanov',
                'fio' => 'Иванов Иван Иванович',
                'phone' => '+79850000001',
                'email' => 'ivanov@example.ru',
            ],
            [
                'login' => 'petrova',
                'fio' => 'Петрова Анна Сергеевна',
                'phone' => '+79850000002',
                'email' => 'petrova@example.ru',
            ],
        ];

        foreach ($demoUsers as $demoUser) {
            User::updateOrCreate(['login' => $demoUser['login']], [
                ...$demoUser,
                'password' => Hash::make('password'),
                'role' => 'user',
            ]);
        }

        $ivanov = User::where('login', 'ivanov')->first();
        $petrova = User::where('login', 'petrova')->first();

        $demoReports = [
            [
                'user_id' => $ivanov->id,
                'category_id' => $categoryModels['Ямы на дорогах'],
                'title' => 'Яма у пешеходного перехода',
                'description' => 'На дороге рядом с переходом появилась глубокая яма, автомобили резко объезжают участок.',
                'status' => 'new',
                'date_incident' => '2026-06-20',
                'contact' => 'email',
            ],
            [
                'user_id' => $ivanov->id,
                'category_id' => $categoryModels['Неисправное освещение'],
                'title' => 'Не горит фонарь во дворе',
                'description' => 'Во дворе дома не работает освещение, вечером участок плохо просматривается.',
                'status' => 'resolved',
                'date_incident' => '2026-06-18',
                'contact' => 'sms',
                'feedback' => 'Фонарь заменили, стало заметно светлее.',
            ],
            [
                'user_id' => $petrova->id,
                'category_id' => $categoryModels['Мусор и свалки'],
                'title' => 'Свалка возле контейнерной площадки',
                'description' => 'Рядом с контейнерами скопился крупногабаритный мусор, территория не убирается.',
                'status' => 'rejected',
                'date_incident' => '2026-06-15',
                'contact' => 'email',
            ],
            [
                'user_id' => $petrova->id,
                'category_id' => $categoryModels['Открытые люки и опасные участки'],
                'title' => 'Открытый люк рядом с остановкой',
                'description' => 'Люк открыт рядом с остановкой общественного транспорта, место опасно для пешеходов.',
                'status' => 'new',
                'date_incident' => '2026-06-22',
                'contact' => 'sms',
            ],
        ];

        foreach ($demoReports as $demoReport) {
            Report::updateOrCreate([
                'user_id' => $demoReport['user_id'],
                'title' => $demoReport['title'],
            ], $demoReport);
        }
    }
}
