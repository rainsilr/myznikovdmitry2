# Нарушениям.Нет

Веб-приложение на Laravel для отправки и обработки городских заявлений.

Последнее обновление проекта: 27.06.2026.

## Стек

- PHP 8.3
- Laravel 13
- MySQL
- HTML5, CSS3, JavaScript
- Vite

## Запуск проекта

1. Создать базу данных MySQL:

```sql
CREATE DATABASE narusheniyam_net CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Установить зависимости:

```bash
composer install
npm install
```

3. Настроить `.env` по примеру `.env.example`.

4. Выполнить миграции и сидеры:

```bash
php artisan migrate:fresh --seed
```

5. Собрать фронтенд:

```bash
npm run build
```

6. Запустить сайт:

```bash
php artisan serve
```

## Администратор

- Логин: `admin`
- Пароль: `adm123`

## Тестовые заявки

После `php artisan migrate:fresh --seed` в базе уже есть несколько демонстрационных заявок.
Если нужно быстро добавить больше заявок для проверки пагинации, фильтрации и смены статусов в админ-панели, выполните:

```bash
php artisan tinker --execute='$user=\App\Models\User::where("role","user")->first(); $categories=\App\Models\Category::all(); for($i=1;$i<=30;$i++){ \App\Models\Report::create(["user_id"=>$user->id,"category_id"=>$categories->random()->id,"title"=>"Тестовая заявка #".$i,"description"=>"Описание тестовой заявки для проверки пагинации, фильтрации и смены статусов.","status"=>collect(["new","resolved","rejected"])->random(),"date_incident"=>now()->subDays(rand(0,20))->format("Y-m-d"),"contact"=>collect(["email","sms"])->random()]); }'
```

После выполнения команды зайдите под администратором на `/admin/reports`.

## Основные возможности

- Регистрация и авторизация пользователей.
- Создание заявления о городской проблеме.
- Просмотр своих заявок.
- Цветное отображение статусов.
- Отзыв по решенной заявке.
- Админ-панель со списком всех заявок, фильтром, пагинацией и сменой статуса.
- Счетчик решенных проблем в подвале.
- Главная страница со слайдером.

## Материалы

Изображения задания находятся в `public/images`.
ER-схема базы данных находится в `db_scheme.png`.
