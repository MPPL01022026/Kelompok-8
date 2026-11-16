<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** MSP-22: backend JSON endpoints, integrated with the MSP-21 Eloquent models. */
class MenuController extends Controller
{
    public function index(): JsonResponse
    {
        $menus = Menu::query()
            ->join('categories', 'menus.category_id', '=', 'categories.id')
            ->select('menus.id', 'menus.name', 'menus.category_id', 'categories.name as category_name', 'menus.price', 'menus.is_available')
            ->orderBy('menus.name')
            ->orderBy('menus.id')
            ->get();

        return response()->json(['data' => $menus]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validatedMenu($request);
        $menu = Menu::create($data + ['is_available' => true]);

        return response()->json(['message' => 'Menu berhasil ditambahkan.', 'data' => $menu], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $menu = Menu::findOrFail($id);
        $menu->update($this->validatedMenu($request));

        return response()->json(['message' => 'Menu berhasil diperbarui.', 'data' => $menu->fresh()]);
    }

    public function destroy(int $id): JsonResponse
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return response()->json(['message' => 'Menu berhasil dihapus.']);
    }

    private function validatedMenu(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'integer', 'min:1', Rule::exists('categories', 'id')],
            'price' => ['required', 'numeric', 'decimal:0,2', 'gt:0', 'max:9999999999.99'],
        ]);
    }
}
