<?php

namespace Database\Seeders;

use App\Models\Category;
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

        // Учетная запись администратора нужна для входа в панель управления.
        User::updateOrCreate(['login' => 'admin'], [
            'fio' => 'Администратор Сайта Нарушениям',
            'phone' => '+7(000)000-00-00',
            'email' => 'admin@example.ru',
            'password' => Hash::make('adm123'),
            'role' => 'admin',
        ]);
    }
}
