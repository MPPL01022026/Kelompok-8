<?php

namespace App\Services;

use App\Models\DiningTable;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

/** Creates an order atomically; prices and user identity are taken from server data. */
class OrderService
{
    private const MAX_CENTS = 999_999_999_999; // DECIMAL(12,2)

    public function create(array $data, User $user): Order
    {
        if (PHP_INT_SIZE < 8) {
            throw new RuntimeException('Perhitungan nominal memerlukan PHP 64-bit.');
        }

        return DB::transaction(function () use ($data, $user) {
            if (! empty($data['dining_table_id'])) {
                $active = DiningTable::whereKey($data['dining_table_id'])->where('is_active', true)->lockForUpdate()->exists();
                if (! $active) {
                    throw ValidationException::withMessages(['dining_table_id' => 'Meja tidak tersedia.']);
                }
            }

            $ids = array_unique(array_map(fn ($item) => (int) $item['menu_id'], $data['items']));
            $menus = Menu::whereIn('id', $ids)->lockForUpdate()->get()->keyBy('id');
            $items = [];
            $total = 0;
            foreach ($data['items'] as $i => $item) {
                $menu = $menus->get((int) $item['menu_id']);
                if (! $menu || ! $menu->is_available) {
                    throw ValidationException::withMessages(["items.$i.menu_id" => 'Menu tidak tersedia.']);
                }
                $qty = (int) $item['quantity'];
                $price = $this->cents($menu->price);
                if ($price > intdiv(self::MAX_CENTS, $qty)) {
                    throw ValidationException::withMessages(["items.$i.quantity" => 'Subtotal melebihi batas penyimpanan.']);
                }
                $subtotal = $price * $qty;
                if ($total > self::MAX_CENTS - $subtotal) {
                    throw ValidationException::withMessages(['items' => 'Total melebihi batas penyimpanan.']);
                }
                $total += $subtotal;
                $items[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $qty,
                    'unit_price' => $this->decimal($price),
                    'subtotal' => $this->decimal($subtotal),
                    'notes' => $item['notes'] ?? null,
                ];
            }

            $order = Order::create([
                'order_number' => 'ORD-'.Str::upper(Str::random(16)),
                'dining_table_id' => $data['dining_table_id'] ?? null,
                'created_by' => $user->getKey(),
                'order_type' => $data['order_type'] ?? 'dine_in',
                'status' => 'menunggu',
                'payment_status' => 'belum_dibayar',
                'total_amount' => $this->decimal($total),
                'notes' => $data['notes'] ?? null,
                'ordered_at' => now(),
            ]);
            $order->items()->createMany($items);

            return $order->load(['items.menu', 'diningTable']);
        });
    }

    private function cents(string $value): int
    {
        if (! preg_match('/\A(\d{1,10})(?:\.(\d{1,2}))?\z/D', $value, $m)) {
            throw ValidationException::withMessages(['items' => 'Harga menu tidak valid.']);
        }
        return ((int) $m[1] * 100) + (int) str_pad($m[2] ?? '', 2, '0');
    }

    private function decimal(int $cents): string
    {
        return intdiv($cents, 100).'.'.str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
    }
}
