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
        

        
        // Сегодня
        $today = $request->user()->expenses()
            ->whereDate('date', $now->format('Y-m-d'))
            ->sum('amount');
            
        // Эта неделя
        $thisWeek = $request->user()->expenses()
            ->whereBetween('date', [
                $now->copy()->startOfWeek()->format('Y-m-d'),
                $now->copy()->endOfWeek()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Этот месяц
        $thisMonth = $request->user()->expenses()
            ->whereBetween('date', [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->copy()->endOfMonth()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Этот год
        $thisYear = $request->user()->expenses()
            ->whereBetween('date', [
                $now->copy()->startOfYear()->format('Y-m-d'),
                $now->copy()->endOfYear()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Прошлый месяц для сравнения
        $lastMonth = $request->user()->expenses()
            ->whereBetween('date', [
                $now->copy()->subMonth()->startOfMonth()->format('Y-m-d'),
                $now->copy()->subMonth()->endOfMonth()->format('Y-m-d')
            ])
            ->sum('amount');
            
        // Средний расход в день за текущий месяц
        $daysInMonth = $now->daysInMonth;
        $avgPerDay = $thisMonth / $daysInMonth;
        
        // Количество транзакций
        $transactionsToday = $request->user()->expenses()
            ->whereDate('date', $now->format('Y-m-d'))
            ->count();
            
        $transactionsThisMonth = $request->user()->expenses()
            ->whereBetween('date', [
                $now->copy()->startOfMonth()->format('Y-m-d'),
                $now->copy()->endOfMonth()->format('Y-m-d')
            ])
            ->count();
            
        // Топ категория за месяц
        $topCategory = $request->user()->expenses()
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
            
        $result = [
            'today' => round((float)$today, 2),
            'this_week' => round((float)$thisWeek, 2),
            'this_month' => round((float)$thisMonth, 2),
            'this_year' => round((float)$thisYear, 2),
            'last_month' => round((float)$lastMonth, 2),
            'avg_per_day' => round((float)$avgPerDay, 2),
            'transactions_today' => $transactionsToday,
            'transactions_this_month' => $transactionsThisMonth,
            'top_category' => $topCategory,
            'month_vs_last_month' => [
                'current' => round((float)$thisMonth, 2),
                'previous' => round((float)$lastMonth, 2),
                'difference' => round((float)$thisMonth - (float)$lastMonth, 2),
                'percentage' => $lastMonth > 0 ? round(((float)$thisMonth - (float)$lastMonth) / (float)$lastMonth * 100, 1) : 0
            ]
        ];
        

        
        return response()->json($result);
    }
}
