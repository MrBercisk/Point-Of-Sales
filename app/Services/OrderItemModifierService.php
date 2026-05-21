<?php
namespace App\Services;

use App\Models\OrderItem;
use App\Models\Modifier;

class OrderItemModifierService
{
    public function attachModifiers(OrderItem $item, array $modifierIds): void
    {
        foreach ($modifierIds as $modifierId) {
            $modifier = Modifier::find($modifierId);
            if (!$modifier) continue;

            // simpan snapshot nama & harga saat order dibuat
            $item->modifiers()->create([
                'modifier_id'    => $modifier->id,
                'name_snapshot'  => $modifier->name,
                'price_snapshot' => $modifier->price,
            ]);

            // kurangi stok bahan baku kalau terhubung ke produk
            if ($modifier->product_id && $modifier->product) {
                $invoice = $item->order?->invoice_number ?? "ORD-{$item->order_id}";
                $modifier->product->recordStockMovement(
                    type:      'out',
                    quantity:  1,
                    reason:    'Add-on/Topping POS',
                    notes:     "Invoice: {$invoice} — Modifier: {$modifier->name}",
                    reference: $item->order,
                );
                $modifier->product->reduceStock(1);
            }
        }

        // recalculate subtotal setelah semua modifier ditambahkan
        $item->recalculateSubtotal();
    }

    public function detachModifiers(OrderItem $item): void
    {
        foreach ($item->modifiers as $orderItemModifier) {
            $modifier = $orderItemModifier->modifier;

            // kembalikan stok bahan baku
            if ($modifier?->product_id && $modifier->product) {
                $invoice = $item->order?->invoice_number ?? "ORD-{$item->order_id}";
                $modifier->product->recordStockMovement(
                    type:      'in',
                    quantity:  1,
                    reason:    'Add-on/Topping dihapus dari order',
                    notes:     "Invoice: {$invoice} — Modifier: {$modifier->name}",
                    reference: $item->order,
                );
                $modifier->product->increaseStock(1);
            }
        }

        $item->modifiers()->delete();
        $item->recalculateSubtotal();
    }
}