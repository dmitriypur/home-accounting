<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->expenses()->with('category');
        
        if ($request->has('category_id') && $request->category_id !== '') {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('date', '<=', $request->date_to);
        }
        
        $expenses = $query->orderBy('date', 'desc')->paginate(15);
        
        return response()->json($expenses);
    }

    public function store(StoreExpenseRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Проверяем что категория принадлежит пользователю
        $category = $request->user()->categories()->find($validated['category_id']);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $expense = $request->user()->expenses()->create([
            'category_id' => $validated['category_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'date' => $validated['date'],
        ]);

        $expense->load('category');

        return response()->json($expense, 201);
    }

    public function show(Request $request, Expense $expense): JsonResponse
    {
        if ($expense->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $expense->load('category');

        return response()->json($expense);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense): JsonResponse
    {
        if ($expense->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validated();

        // Проверяем что категория принадлежит пользователю
        $category = $request->user()->categories()->find($validated['category_id']);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $expense->update([
            'category_id' => $validated['category_id'],
            'amount' => $validated['amount'],
            'description' => $validated['description'],
            'date' => $validated['date'],
        ]);

        $expense->load('category');

        return response()->json($expense);
    }

    public function destroy(Request $request, Expense $expense): JsonResponse
    {
        if ($expense->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
