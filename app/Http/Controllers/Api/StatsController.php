<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'nullable|in:day,week,month,year',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $period = $request->get('period', 'month');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Если период не указан, устанавливаем даты по умолчанию
        if (!$dateFrom || !$dateTo) {
            $now = Carbon::now();
            switch ($period) {
                case 'day':
                    $dateFrom = $now->format('Y-m-d');
                    $dateTo = $now->format('Y-m-d');
                    break;
                case 'week':
                    $dateFrom = $now->startOfWeek()->format('Y-m-d');
                    $dateTo = $now->endOfWeek()->format('Y-m-d');
                    break;
                case 'month':
                    $dateFrom = $now->startOfMonth()->format('Y-m-d');
                    $dateTo = $now->endOfMonth()->format('Y-m-d');
                    break;
                case 'year':
                    $dateFrom = $now->startOfYear()->format('Y-m-d');
                    $dateTo = $now->endOfYear()->format('Y-m-d');
                    break;
            }
        }

        // Статистика по категориям расходов
        $expenseCategoryStats = $request->user()->expenses()
            ->select('category_id', 'categories.name', 'categories.color', 'categories.icon')
            ->selectRaw('SUM(amount) as total_amount')
            ->selectRaw('COUNT(*) as count')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->groupBy('category_id', 'categories.name', 'categories.color', 'categories.icon')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Статистика по категориям доходов
        $incomeCategoryStats = $request->user()->incomes()
            ->select('category_id', 'categories.name', 'categories.color', 'categories.icon')
            ->selectRaw('SUM(amount) as total_amount')
            ->selectRaw('COUNT(*) as count')
            ->join('categories', 'incomes.category_id', '=', 'categories.id')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->groupBy('category_id', 'categories.name', 'categories.color', 'categories.icon')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Статистика по дням для расходов
        $dailyExpenseStats = $request->user()->expenses()
            ->select('date')
            ->selectRaw('SUM(amount) as total_amount')
            ->selectRaw('COUNT(*) as count')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Статистика по дням для доходов
        $dailyIncomeStats = $request->user()->incomes()
            ->select('date')
            ->selectRaw('SUM(amount) as total_amount')
            ->selectRaw('COUNT(*) as count')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Объединенная статистика по дням (баланс)
        $allDates = collect();
        $dailyExpenseStats->each(function ($item) use ($allDates) {
            $allDates->put($item->date, ['date' => $item->date, 'expenses' => $item->total_amount, 'incomes' => 0]);
        });
        $dailyIncomeStats->each(function ($item) use ($allDates) {
            if ($allDates->has($item->date)) {
                $allDates[$item->date]['incomes'] = $item->total_amount;
            } else {
                $allDates->put($item->date, ['date' => $item->date, 'expenses' => 0, 'incomes' => $item->total_amount]);
            }
        });
        
        $dailyBalanceStats = $allDates->map(function ($item) {
            return [
                'date' => $item['date'],
                'expenses' => round((float)$item['expenses'], 2),
                'incomes' => round((float)$item['incomes'], 2),
                'balance' => round((float)$item['incomes'] - (float)$item['expenses'], 2)
            ];
        })->sortBy('date')->values();

        return response()->json([
            'period' => $period,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'expenses' => [
                'category_stats' => $expenseCategoryStats,
                'daily_stats' => $dailyExpenseStats,
                'total_amount' => round((float)$expenseCategoryStats->sum('total_amount'), 2),
                'total_count' => $expenseCategoryStats->sum('count'),
            ],
            'incomes' => [
                'category_stats' => $incomeCategoryStats,
                'daily_stats' => $dailyIncomeStats,
                'total_amount' => round((float)$incomeCategoryStats->sum('total_amount'), 2),
                'total_count' => $incomeCategoryStats->sum('count'),
            ],
            'balance' => [
                'daily_stats' => $dailyBalanceStats,
                'total_balance' => round((float)$incomeCategoryStats->sum('total_amount') - (float)$expenseCategoryStats->sum('total_amount'), 2),
            ]
        ]);
    }
}
