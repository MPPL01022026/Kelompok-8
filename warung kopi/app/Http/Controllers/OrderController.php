<?php

namespace App\Http\Controllers;

use App\Models\DiningTable;
use App\Models\Menu;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function create(): JsonResponse
    {
        return response()->json(['data' => [
            'menus' => Menu::with('category:id,name')->where('is_available', true)->orderBy('name')->get(['id', 'category_id', 'name', 'price', 'is_available']),
            'dining_tables' => DiningTable::where('is_active', true)->orderBy('number')->get(['id', 'number']),
        ]]);
    }

    public function store(Request $request, OrderService $orders): JsonResponse
    {
        $data = $request->validate([
            'order_type' => ['nullable', Rule::in(['dine_in', 'take_away'])],
            'dining_table_id' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'list', 'min:1', 'max:100'],
            'items.*.menu_id' => ['required', 'integer', 'min:1'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:65535'],
            'items.*.notes' => ['nullable', 'string', 'max:255'],
        ]);
        $order = $orders->create($data, $request->user());

        return response()->json(['message' => 'Pesanan berhasil dicatat.', 'data' => $order], 201);
    }
}
