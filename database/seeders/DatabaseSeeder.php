<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем тестового пользователя
        $user = User::create([
            'name' => 'Тестовый пользователь',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // Создаем категории
        $categories = [
            ['name' => 'Продукты', 'icon' => '🛒', 'color' => '#10B981'],
            ['name' => 'Транспорт', 'icon' => '🚗', 'color' => '#3B82F6'],
            ['name' => 'Развлечения', 'icon' => '🎬', 'color' => '#8B5CF6'],
            ['name' => 'Здоровье', 'icon' => '💊', 'color' => '#EF4444'],
            ['name' => 'Одежда', 'icon' => '👕', 'color' => '#F59E0B'],
            ['name' => 'Дом', 'icon' => '🏠', 'color' => '#6B7280'],
        ];

        foreach ($categories as $categoryData) {
            $category = $user->categories()->create($categoryData);
            
            // Создаем несколько расходов для каждой категории
            for ($i = 0; $i < rand(3, 8); $i++) {
                $user->expenses()->create([
                    'category_id' => $category->id,
                    'amount' => rand(50, 5000) / 10, // от 5 до 500
                    'description' => $this->getRandomDescription($category->name),
                    'date' => now()->subDays(rand(0, 30))->format('Y-m-d'),
                ]);
            }
        }
    }

    private function getRandomDescription(string $categoryName): string
    {
        $descriptions = [
            'Продукты' => ['Покупка в супермаркете', 'Овощи и фрукты', 'Мясо и рыба', 'Молочные продукты'],
            'Транспорт' => ['Бензин', 'Общественный транспорт', 'Такси', 'Парковка'],
            'Развлечения' => ['Кино', 'Ресторан', 'Концерт', 'Игры'],
            'Здоровье' => ['Лекарства', 'Врач', 'Анализы', 'Витамины'],
            'Одежда' => ['Рубашка', 'Обувь', 'Аксессуары', 'Куртка'],
            'Дом' => ['Коммунальные услуги', 'Ремонт', 'Мебель', 'Бытовая техника'],
        ];

        $categoryDescriptions = $descriptions[$categoryName] ?? ['Разное'];
        return $categoryDescriptions[array_rand($categoryDescriptions)];
    }
}
