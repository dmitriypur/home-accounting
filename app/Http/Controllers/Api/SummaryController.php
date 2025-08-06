<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SummaryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $now = Carbon::now();
        
        // РАСХОДЫ
        // Сегодня
        $expensesToday = $request->user()->expenses()
            ->whereDate('date', $now->format('Y-m-d'))
            ->sum('amount');
            
        // Эта неделя
        $expensesThisWeek = $request->user()->expenses()
            ->whereBetween('date', [
                $now->copy()->startOfWeek()->format('Y-m-d'),
                $now->copy()->endOfWeek()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Этот месяц
        $expensesThisMonth = $request->user()->expenses()
            ->whereBetween('date', [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->copy()->endOfMonth()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Этот год
        $expensesThisYear = $request->user()->expenses()
            ->whereBetween('date', [
                $now->copy()->startOfYear()->format('Y-m-d'),
                $now->copy()->endOfYear()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Прошлый месяц для сравнения
        $expensesLastMonth = $request->user()->expenses()
            ->whereBetween('date', [
                $now->copy()->subMonth()->startOfMonth()->format('Y-m-d'),
                $now->copy()->subMonth()->endOfMonth()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // ДОХОДЫ
        // Сегодня
        $incomesToday = $request->user()->incomes()
            ->whereDate('date', $now->format('Y-m-d'))
            ->sum('amount');
            
        // Эта неделя
        $incomesThisWeek = $request->user()->incomes()
            ->whereBetween('date', [
                $now->copy()->startOfWeek()->format('Y-m-d'),
                $now->copy()->endOfWeek()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Этот месяц
        $incomesThisMonth = $request->user()->incomes()
            ->whereBetween('date', [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->copy()->endOfMonth()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Этот год
        $incomesThisYear = $request->user()->incomes()
            ->whereBetween('date', [
                $now->copy()->startOfYear()->format('Y-m-d'),
                $now->copy()->endOfYear()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Прошлый месяц для сравнения
        $incomesLastMonth = $request->user()->incomes()
            ->whereBetween('date', [
                $now->copy()->subMonth()->startOfMonth()->format('Y-m-d'),
                $now->copy()->subMonth()->endOfMonth()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Баланс (доходы - расходы)
        $balanceToday = $incomesToday - $expensesToday;
        $balanceThisWeek = $incomesThisWeek - $expensesThisWeek;
        $balanceThisMonth = $incomesThisMonth - $expensesThisMonth;
        $balanceThisYear = $incomesThisYear - $expensesThisYear;
        $balanceLastMonth = $incomesLastMonth - $expensesLastMonth;
        
        // Средний расход и доход в день за текущий месяц
        $daysInMonth = $now->daysInMonth;
        $avgExpensePerDay = $expensesThisMonth / $daysInMonth;
        $avgIncomePerDay = $incomesThisMonth / $daysInMonth;
        
        // Количество транзакций
        $expenseTransactionsToday = $request->user()->expenses()
            ->whereDate('date', $now->format('Y-m-d'))
            ->count();
            
        $incomeTransactionsToday = $request->user()->incomes()
            ->whereDate('date', $now->format('Y-m-d'))
            ->count();
            
        $expenseTransactionsThisMonth = $request->user()->expenses()
            ->whereBetween('date', [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->copy()->endOfMonth()->format('Y-m-d')
            ])
            ->count();
            
        $incomeTransactionsThisMonth = $request->user()->incomes()
            ->whereBetween('date', [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->copy()->endOfMonth()->format('Y-m-d')
            ])
            ->count();
            
        // Топ категория расходов за месяц
        $topExpenseCategory = $request->user()->expenses()
            ->select('categories.name', 'categories.icon')
            ->selectRaw('SUM(amount) as total')
            ->selectRaw('COUNT(*) as count')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->whereBetween('date', [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->copy()->endOfMonth()->format('Y-m-d')
            ])
            ->groupBy('category_id', 'categories.name', 'categories.icon')
            ->orderBy('total', 'desc')
            ->first();
            
        // Топ категория доходов за месяц
        $topIncomeCategory = $request->user()->incomes()
            ->select('categories.name', 'categories.icon')
            ->selectRaw('SUM(amount) as total')
            ->selectRaw('COUNT(*) as count')
            ->join('categories', 'incomes.category_id', '=', 'categories.id')
            ->whereBetween('date', [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->copy()->endOfMonth()->format('Y-m-d')
            ])
            ->groupBy('category_id', 'categories.name', 'categories.icon')
            ->orderBy('total', 'desc')
            ->first();
            
        $result = [
            'expenses' => [
                'today' => round((float)$expensesToday, 2),
                'this_week' => round((float)$expensesThisWeek, 2),
                'this_month' => round((float)$expensesThisMonth, 2),
                'this_year' => round((float)$expensesThisYear, 2),
                'last_month' => round((float)$expensesLastMonth, 2),
                'avg_per_day' => round((float)$avgExpensePerDay, 2),
                'transactions_today' => $expenseTransactionsToday,
                'transactions_this_month' => $expenseTransactionsThisMonth,
                'top_category' => $topExpenseCategory,
            ],
            'incomes' => [
                'today' => round((float)$incomesToday, 2),
                'this_week' => round((float)$incomesThisWeek, 2),
                'this_month' => round((float)$incomesThisMonth, 2),
                'this_year' => round((float)$incomesThisYear, 2),
                'last_month' => round((float)$incomesLastMonth, 2),
                'avg_per_day' => round((float)$avgIncomePerDay, 2),
                'transactions_today' => $incomeTransactionsToday,
                'transactions_this_month' => $incomeTransactionsThisMonth,
                'top_category' => $topIncomeCategory,
            ],
            'balance' => [
                'today' => round((float)$balanceToday, 2),
                'this_week' => round((float)$balanceThisWeek, 2),
                'this_month' => round((float)$balanceThisMonth, 2),
                'this_year' => round((float)$balanceThisYear, 2),
                'last_month' => round((float)$balanceLastMonth, 2),
            ],
            'comparison' => [
                'expenses' => [
                    'current' => round((float)$expensesThisMonth, 2),
                    'previous' => round((float)$expensesLastMonth, 2),
                    'difference' => round((float)$expensesThisMonth - (float)$expensesLastMonth, 2),
                    'percentage' => $expensesLastMonth > 0 ? round(((float)$expensesThisMonth - (float)$expensesLastMonth) / (float)$expensesLastMonth * 100, 1) : 0
                ],
                'incomes' => [
                    'current' => round((float)$incomesThisMonth, 2),
                    'previous' => round((float)$incomesLastMonth, 2),
                    'difference' => round((float)$incomesThisMonth - (float)$incomesLastMonth, 2),
                    'percentage' => $incomesLastMonth > 0 ? round(((float)$incomesThisMonth - (float)$incomesLastMonth) / (float)$incomesLastMonth * 100, 1) : 0
                ],
                'balance' => [
                    'current' => round((float)$balanceThisMonth, 2),
                    'previous' => round((float)$balanceLastMonth, 2),
                    'difference' => round((float)$balanceThisMonth - (float)$balanceLastMonth, 2)
                ]
            ]
        ];
        

        
        return response()->json($result);
    }
}
