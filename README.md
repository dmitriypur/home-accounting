# Home Accounting API

Простое API для учета домашних расходов, построенное на Laravel 11 с использованием Laravel Sanctum для аутентификации.

## Возможности

- Регистрация и аутентификация пользователей
- Управление категориями расходов
- Учет расходов с привязкой к категориям
- Статистика расходов по категориям и периодам
- Сводные данные (сегодня, неделя, месяц, год)

## Запуск проекта

### Backend (Laravel API)
1. Клонируйте репозиторий
2. Установите зависимости: `composer install`
3. Скопируйте `.env.example` в `.env` и настройте базу данных
4. Выполните миграции: `php artisan migrate`
5. Заполните базу тестовыми данными: `php artisan db:seed`
6. Запустите сервер: `php artisan serve`

### Frontend (Vue.js SPA)
1. Установите Node.js зависимости: `npm install`
2. Запустите Vite dev сервер: `npm run dev`
3. Откройте браузер по адресу: `http://127.0.0.1:8000`

### Тестовые данные
После выполнения `php artisan db:seed` будет создан тестовый пользователь:
- Email: `test@example.com`
- Пароль: `password`

## API Endpoints

### Аутентификация

- `POST /api/register` - Регистрация
- `POST /api/login` - Вход
- `POST /api/logout` - Выход (требует токен)
- `GET /api/user` - Получить данные пользователя (требует токен)

### Категории

- `GET /api/categories` - Список категорий пользователя
- `POST /api/categories` - Создать категорию
- `GET /api/categories/{id}` - Получить категорию
- `PUT /api/categories/{id}` - Обновить категорию
- `DELETE /api/categories/{id}` - Удалить категорию

### Расходы

- `GET /api/expenses` - Список расходов (с фильтрами)
- `POST /api/expenses` - Создать расход
- `GET /api/expenses/{id}` - Получить расход
- `PUT /api/expenses/{id}` - Обновить расход
- `DELETE /api/expenses/{id}` - Удалить расход

### Статистика и сводка

- `GET /api/stats` - Статистика по категориям и периодам
- `GET /api/summary` - Сводные данные

## Тестовые данные

**Пользователь:**
- Email: `test@example.com`
- Пароль: `password`

## Примеры запросов

### Вход в систему
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```

### Создание категории
```bash
curl -X POST http://127.0.0.1:8000/api/categories \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Кафе","icon":"☕","color":"#8B4513"}'
```

### Создание расхода
```bash
curl -X POST http://127.0.0.1:8000/api/expenses \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"category_id":1,"amount":150.50,"description":"Обед в кафе","date":"2025-08-06"}'
```

### Получение статистики
```bash
curl -X GET "http://127.0.0.1:8000/api/stats?period=month" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Структура проекта

- `app/Models/` - Модели Eloquent (User, Category, Expense)
- `app/Http/Controllers/Api/` - API контроллеры
- `app/Http/Requests/` - Классы валидации запросов
- `database/migrations/` - Миграции базы данных
- `routes/api.php` - API маршруты

## Технологии

- Laravel 11
- Laravel Sanctum (аутентификация)
- MySQL/SQLite (база данных)
- PHP 8.2+
