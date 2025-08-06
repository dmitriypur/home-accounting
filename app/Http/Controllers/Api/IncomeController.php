<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\Income;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->incomes()->with('category');
        
        if ($request->has('category_id') && $request->category_id !== '') {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('date', '<=', $request->date_to);
        }
        
        $incomes = $query->orderBy('date', 'desc')->paginate(15);
        
        return response()->json($incomes);
    }

    public function store(StoreIncomeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Проверяем что категория принадлежит пользователю
        $category = $request->user()->categories()->find($validated['category_id']);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $income = $request->user()->incomes()->create([
            'category_id' => $validated['category_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'date' => $validated['date'],
        ]);

        $income->load('category');

        return response()->json($income, 201);
    }

    public function show(Request $request, Income $income): JsonResponse
    {
        if ($income->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $income->load('category');

        return response()->json($income);
    }

    public function update(UpdateIncomeRequest $request, Income $income): JsonResponse
    {
        if ($income->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validated();

        // Проверяем что категория принадлежит пользователю
        $category = $request->user()->categories()->find($validated['category_id']);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $income->update([
            'category_id' => $validated['category_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'date' => $validated['date'],
        ]);

        $income->load('category');

        return response()->json($income);
    }

    public function destroy(Request $request, Income $income): JsonResponse
    {
        if ($income->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $income->delete();

        return response()->json(['message' => 'Income deleted successfully']);
    }
}