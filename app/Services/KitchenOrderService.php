<?php

namespace App\Services;

use App\Models\KitchenOrder;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;

class KitchenOrderService
{
    /**
     * Buat KitchenOrder dari order yang baru co.
     * cuma item dengan produk needs_preparation = true yang masuk.
     */
    public function createFromOrder(Order $order, Collection $cart): ?KitchenOrder
    {
        $productIds = $cart->pluck('product_id')->unique()->toArray();

        $prepProducts = Product::whereIn('id', $productIds)
            ->where('needs_preparation', true)
            ->pluck('id')
            ->toArray();

        if (empty($prepProducts)) {
            return null;
        }

        $kitchenItems = $cart
            ->filter(fn($item) => in_array($item['product_id'], $prepProducts))
            ->map(fn($item) => [
                'product_id' => $item['product_id'],
                'name'       => $item['name'],
                'quantity'   => $item['quantity'],
                'modifiers'  => $item['modifiers'] ?? [],
                'note'       => $item['note'] ?? null,
            ])
            ->values()
            ->toArray();

        if (empty($kitchenItems)) {
            return null;
        }

        return KitchenOrder::create([
            'order_id'       => $order->id,
            'invoice_number' => $order->invoice_number ?? "ORD-{$order->id}",
            'customer_name'  => $order->customer_name,
            'customer_class' => $order->student?->class ?? null,
            'status'         => 'pending',
            'items'          => $kitchenItems,
        ]);
    }
}