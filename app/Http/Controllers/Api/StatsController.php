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

        // Статистика по категориям
        $categoryStats = $request->user()->expenses()
            ->select('category_id', 'categories.name', 'categories.color', 'categories.icon')
            ->selectRaw('SUM(amount) as total_amount')
            ->selectRaw('COUNT(*) as count')
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->groupBy('category_id', 'categories.name', 'categories.color', 'categories.icon')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Статистика по дням
        $dailyStats = $request->user()->expenses()
            ->select('date')
            ->selectRaw('SUM(amount) as total_amount')
            ->selectRaw('COUNT(*) as count')
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'period' => $period,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'category_stats' => $categoryStats,
            'daily_stats' => $dailyStats,
            'total_amount' => $categoryStats->sum('total_amount'),
            'total_count' => $categoryStats->sum('count'),
        ]);
    }
}
