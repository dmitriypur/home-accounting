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

- `POST /api/register` - Регистрация нового пользователя
- `POST /api/login` - Вход в систему
- `POST /api/logout` - Выход из системы
- `GET /api/user` - Получение данных текущего пользователя

### Категории

- `GET /api/categories` - Получение всех категорий пользователя
- `POST /api/categories` - Создание новой категории
- `GET /api/categories/{id}` - Получение конкретной категории
- `PUT /api/categories/{id}` - Обновление категории
- `DELETE /api/categories/{id}` - Удаление категории

### Расходы

- `GET /api/expenses` - Получение всех расходов пользователя
- `POST /api/expenses` - Создание нового расхода
- `GET /api/expenses/{id}` - Получение конкретного расхода
- `PUT /api/expenses/{id}` - Обновление расхода
- `DELETE /api/expenses/{id}` - Удаление расхода

### Доходы

- `GET /api/incomes` - Получение всех доходов пользователя
- `POST /api/incomes` - Создание нового дохода
- `GET /api/incomes/{id}` - Получение конкретного дохода
- `PUT /api/incomes/{id}` - Обновление дохода
- `DELETE /api/incomes/{id}` - Удаление дохода

### Статистика

- `GET /api/summary` - Получение сводной статистики (расходы, доходы, баланс)
- `GET /api/stats` - Получение детальной статистики за период (расходы, доходы, баланс)

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
