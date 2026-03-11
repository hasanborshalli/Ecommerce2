<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $subtotal     = $this->cartSubtotal($cart);
        $freeShipping = (float) SiteSetting::get('free_shipping_over', 150);
        $shippingCost = (float) SiteSetting::get('shipping_cost', 9);
        $shipping     = $subtotal >= $freeShipping ? 0 : $shippingCost;
        $total        = $subtotal + $shipping;

        return view('checkout', compact('cart', 'subtotal', 'shipping', 'total'));
    }

    public function submit(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'customer_name'    => 'required|string|max:100',
            'customer_email'   => 'required|email|max:150',
            'customer_phone'   => 'nullable|string|max:30',
            'shipping_address' => 'required|string|max:200',
            'shipping_city'    => 'required|string|max:100',
            'notes'            => 'nullable|string|max:500',
        ]);

        $subtotal     = $this->cartSubtotal($cart);
        $freeShipping = (float) SiteSetting::get('free_shipping_over', 150);
        $shippingCost = (float) SiteSetting::get('shipping_cost', 9);
        $shipping     = $subtotal >= $freeShipping ? 0 : $shippingCost;
        $total        = $subtotal + $shipping;

        DB::transaction(function () use ($validated, $cart, $subtotal, $shipping, $total) {

            // ── Build cost snapshot ──────────────────────────────
            $costTotal = 0;
            $lineItems = [];

            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                if (!$product) continue;

                $qty        = (int) $item['quantity'];
                $price      = (float) $item['price'];
                $cost       = (float) $product->cost_price;
                $lineTotal  = round($price * $qty, 2);
                $lineCost   = round($cost  * $qty, 2);
                $lineProfit = round($lineTotal - $lineCost, 2);
                $costTotal += $lineCost;

                $lineItems[] = [
                    'product_id'    => $product->id,
                    'product_name'  => $item['name'],
                    'product_sku'   => $product->sku,
                    'product_price' => $price,
                    'product_cost'  => $cost,
                    'quantity'      => $qty,
                    'variant'       => !empty($item['variant']) ? json_encode($item['variant']) : null,
                    'line_total'    => $lineTotal,
                    'line_cost'     => $lineCost,
                    'line_profit'   => $lineProfit,
                ];

                // Deduct stock + log movement
                $product->deductStock($qty, 'order', null, 'Sale');
            }

            // ── Create order ────────────────────────────────────
            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'customer_name'    => $validated['customer_name'],
                'customer_email'   => $validated['customer_email'],
                'customer_phone'   => $validated['customer_phone'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'shipping_city'    => $validated['shipping_city'],
                'subtotal'         => $subtotal,
                'shipping_cost'    => $shipping,
                'discount'         => 0,
                'total'            => $total,
                'cost_total'       => round($costTotal, 2),
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'payment_method'   => 'cash_on_delivery',
                'notes'            => $validated['notes'] ?? null,
            ]);

            // ── Create order items ───────────────────────────────
            foreach ($lineItems as $li) {
                $order->items()->create($li);
            }

            // ── Update order reference in stock movements ────────
            // Update the movement's reference_id to the real order id
            \App\Models\StockMovement::whereNull('reference_id')
                ->where('reference_type', 'order')
                ->where('created_at', '>=', now()->subMinutes(1))
                ->update(['reference_id' => $order->id]);

            // ── Clear cart, store order number in session ────────
            session(['cart' => [], 'last_order_number' => $order->order_number]);
            session(['last_order_id' => $order->id]);
        });

        return redirect()->route('checkout.confirmation', session('last_order_id'));
    }

    public function confirmation(Order $order)
    {
        // Prevent visiting someone else's confirmation
        if ($order->order_number !== session('last_order_number')) {
            abort(403);
        }

        $order->load('items');
        return view('order-confirmation', compact('order'));
    }

    // ── Helper ───────────────────────────────────────────────

    private function cartSubtotal(array $cart): float
    {
        return round(array_sum(array_map(
            fn($i) => $i['price'] * $i['quantity'],
            $cart
        )), 2);
    }
}