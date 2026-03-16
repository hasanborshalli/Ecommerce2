<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\HeroSlide;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\SiteSetting;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Site Settings ────────────────────────────────────────────
        $settings = [
            'site_name'           => 'Vaulted',
            'site_tagline'        => 'Built to last. Designed to impress.',
            'site_logo'           => '',
            'currency_symbol'     => '$',
            'shipping_cost'       => '9.00',
            'free_shipping_over'  => '150',
            'site_phone'          => '+1 (800) 555-0199',
            'site_email'          => 'hello@vaulted.com',
            'site_address'        => '100 Commerce St, New York, NY 10001',
            'social_instagram'    => 'https://instagram.com/',
            'social_facebook'     => '',
            'social_twitter'      => '',
            'footer_about'        => 'Premium goods for people who value quality over quantity.',
            'meta_title'          => 'Vaulted — Premium Goods',
            'meta_description'    => 'Shop premium, long-lasting products. Free shipping over $150.',
            'admin_email_notify'  => 'admin@vaulted.com',
            'low_stock_notify'    => '1', // send notification when low stock
        ];
        foreach ($settings as $key => $value) {
            SiteSetting::set($key, $value);
        }

        // ── Hero Slides ──────────────────────────────────────────────
        HeroSlide::insert([
            [
                'headline'     => 'Built Different.',
                'subheadline'  => 'Premium products engineered for people who refuse to settle.',
                'button_text'  => 'Shop Now',
                'button_url'   => '/shop',
                'image'        => '',
                'overlay_color'=> 'rgba(11,22,41,0.55)',
                'is_active'    => true,
                'sort_order'   => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'headline'     => 'New Season. New Standards.',
                'subheadline'  => 'Explore our latest arrivals — crafted for performance and style.',
                'button_text'  => 'View New Arrivals',
                'button_url'   => '/shop?filter=new',
                'image'        => '',
                'overlay_color'=> 'rgba(11,22,41,0.45)',
                'is_active'    => true,
                'sort_order'   => 2,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);

            // ── Categories ───────────────────────────────────────────────
        $categories = [
            ['name' => 'T-Shirts',  'slug' => 't-shirts',  'sort_order' => 1],
            ['name' => 'Hoodies',   'slug' => 'hoodies',   'sort_order' => 2],
            ['name' => 'Caps',      'slug' => 'caps',      'sort_order' => 3],
            ['name' => 'Mugs',      'slug' => 'mugs',      'sort_order' => 4],
            ['name' => 'Jackets',   'slug' => 'jackets',   'sort_order' => 5],
            ['name' => 'Featured',  'slug' => 'featured',  'sort_order' => 6],
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $categoryMap[$cat['slug']] = Category::create(array_merge($cat, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ── Suppliers ────────────────────────────────────────────────
        $supplierA = Supplier::create([
            'name'           => 'Prime Textiles Ltd.',
            'contact_person' => 'Sarah Chen',
            'email'          => 'sarah@primetextiles.com',
            'phone'          => '+1 555 010 2030',
            'is_active'      => true,
        ]);
        $supplierB = Supplier::create([
            'name'           => 'TechSource International',
            'contact_person' => 'Marco Russo',
            'email'          => 'marco@techsource.io',
            'phone'          => '+1 555 040 5060',
            'is_active'      => true,
        ]);

        // ── Products ─────────────────────────────────────────────────
        $products = [
            // T-Shirts
            [
                'category'    => 't-shirts',
                'name'        => 'Heather Grey Logo T-Shirt',
                'price'       => 32.00,
                'cost_price'  => 10.00,
                'stock'       => 60,
                'variants'    => [
                    'Size'  => ['S', 'M', 'L', 'XL', 'XXL'],
                    'Color' => ['Heather Grey']
                ],
                'is_featured' => true,
                'is_new'      => true,
            ],
            [
                'category'    => 't-shirts',
                'name'        => 'Charcoal Chest Logo T-Shirt',
                'price'       => 30.00,
                'cost_price'  => 9.00,
                'stock'       => 55,
                'variants'    => [
                    'Size'  => ['S', 'M', 'L', 'XL'],
                    'Color' => ['Charcoal']
                ],
                'is_featured' => true,
            ],

            // Hoodies
            [
                'category'    => 'hoodies',
                'name'        => 'Charcoal Logo Hoodie',
                'price'       => 79.00,
                'cost_price'  => 28.00,
                'stock'       => 30,
                'variants'    => [
                    'Size'  => ['S', 'M', 'L', 'XL', 'XXL'],
                    'Color' => ['Charcoal']
                ],
                'is_featured' => true,
                'is_new'      => true,
            ],
            [
                'category'    => 'hoodies',
                'name'        => 'Heather Grey Logo Hoodie',
                'price'       => 75.00,
                'cost_price'  => 27.00,
                'stock'       => 28,
                'variants'    => [
                    'Size'  => ['S', 'M', 'L', 'XL'],
                    'Color' => ['Heather Grey']
                ],
                'is_featured' => true,
            ],

            // Caps
            [
                'category'    => 'caps',
                'name'        => 'Embroidered Logo Cap',
                'price'       => 29.00,
                'cost_price'  => 9.00,
                'stock'       => 50,
                'variants'    => [
                    'Size'  => ['One Size'],
                    'Color' => ['Grey']
                ],
                'is_featured' => true,
                'is_new'      => true,
            ],

            // Mugs
            [
                'category'    => 'mugs',
                'name'        => 'Ceramic Logo Mug',
                'price'       => 18.00,
                'cost_price'  => 5.50,
                'stock'       => 80,
                'variants'    => [
                    'Size'  => ['11oz'],
                    'Color' => ['White']
                ],
                'is_featured' => true,
            ],

            // Jackets
            [
                'category'    => 'jackets',
                'name'        => 'Charcoal Logo Bomber Jacket',
                'price'       => 110.00,
                'cost_price'  => 42.00,
                'stock'       => 18,
                'variants'    => [
                    'Size'  => ['S', 'M', 'L', 'XL'],
                    'Color' => ['Charcoal']
                ],
                'is_featured' => true,
                'is_new'      => true,
            ],

            // Featured / extra demo items based on same brand style
            [
                'category'    => 'featured',
                'name'        => 'Minimal Logo Hoodie',
                'price'       => 72.00,
                'cost_price'  => 26.00,
                'stock'       => 20,
                'variants'    => [
                    'Size'  => ['S', 'M', 'L', 'XL'],
                    'Color' => ['Ash Grey', 'Charcoal']
                ],
                'is_featured' => true,
            ],
            [
                'category'    => 'featured',
                'name'        => 'Studio Coffee Mug',
                'price'       => 20.00,
                'cost_price'  => 6.00,
                'stock'       => 45,
                'variants'    => [
                    'Size'  => ['11oz', '15oz'],
                    'Color' => ['White']
                ],
                'is_new'      => true,
            ],
            [
                'category'    => 'featured',
                'name'        => 'Essential Logo Tee',
                'price'       => 28.00,
                'cost_price'  => 8.50,
                'stock'       => 24,
                'variants'    => [
                    'Size'  => ['S', 'M', 'L', 'XL'],
                    'Color' => ['Heather Grey', 'Charcoal']
                ],
                'is_on_sale'  => true,
                'sale_price'  => 22.00,
            ],
        ];

        $productModels = [];
        foreach ($products as $i => $data) {
            $name = $data['name'];
            $product = Product::create([
                'category_id'            => $categoryMap[$data['category']]->id,
                'name'                   => $name,
                'slug'                   => Str::slug($name),
                'sku'                    => 'VLT-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'short_description'      => 'Premium quality ' . strtolower($name) . '. Built to last.',
                'description'            => '<p>The <strong>' . $name . '</strong> is crafted from premium materials for lasting performance and style. Every detail is considered — from material selection to finishing. Designed for those who demand the best.</p><p>Free returns within 30 days.</p>',
                'price'                  => $data['price'],
                'sale_price'             => $data['sale_price'] ?? null,
                'cost_price'             => $data['cost_price'],
                'stock'                  => $data['stock'],
                'low_stock_threshold'    => 5,
                'show_when_out_of_stock' => true,
                'variants'               => $data['variants'] ?? null,
                'is_active'              => true,
                'is_featured'            => $data['is_featured'] ?? false,
                'is_new'                 => $data['is_new'] ?? false,
                'is_on_sale'             => $data['is_on_sale'] ?? false,
                'sort_order'             => $i + 1,
                'meta_title'             => $name . ' — Vaulted',
                'meta_description'       => 'Buy ' . $name . ' at Vaulted. Premium quality, fast shipping.',
            ]);
            $productModels[] = $product;

            // Log initial stock as a manual 'in' movement
            StockMovement::create([
                'product_id'     => $product->id,
                'type'           => 'in',
                'quantity'       => $data['stock'],
                'stock_before'   => 0,
                'stock_after'    => $data['stock'],
                'reference_type' => 'manual',
                'reference_id'   => null,
                'unit_cost'      => $data['cost_price'],
                'notes'          => 'Initial stock',
            ]);
        }

        // ── Sample Purchase Order ────────────────────────────────────
        $po = PurchaseOrder::create([
            'supplier_id'      => $supplierA->id,
            'reference_number' => 'PO-2024-0001',
            'order_date'       => now()->subDays(14)->toDateString(),
            'received_date'    => now()->subDays(7)->toDateString(),
            'status'           => 'received',
            'notes'            => 'First bulk order for Q1',
            'total_cost'       => 0,
        ]);

        $poItems = [
            ['product' => $productModels[0], 'qty' => 50, 'cost' => 28.00],
            ['product' => $productModels[1], 'qty' => 30, 'cost' => 38.00],
            ['product' => $productModels[5], 'qty' => 60, 'cost' => 12.00],
        ];
        $poTotal = 0;
        foreach ($poItems as $item) {
            $lineTotal = $item['qty'] * $item['cost'];
            $poTotal  += $lineTotal;
            PurchaseOrderItem::create([
                'purchase_order_id' => $po->id,
                'product_id'        => $item['product']->id,
                'quantity_ordered'  => $item['qty'],
                'quantity_received' => $item['qty'],
                'cost_per_unit'     => $item['cost'],
                'total_cost'        => $lineTotal,
            ]);
        }
        $po->update(['total_cost' => $poTotal]);

        // ── Sample Orders (for reports demo data) ────────────────────
        $orderData = [
            ['name' => 'Alex Jordan',   'email' => 'alex@email.com',   'city' => 'New York',    'days' => 2,  'items' => [0, 5]],
            ['name' => 'Maria Costa',   'email' => 'maria@email.com',  'city' => 'Los Angeles',  'days' => 5,  'items' => [3, 6]],
            ['name' => 'James Lee',     'email' => 'james@email.com',  'city' => 'Chicago',      'days' => 8,  'items' => [8, 7]],
            ['name' => 'Nour Haddad',   'email' => 'nour@email.com',   'city' => 'Houston',      'days' => 12, 'items' => [9, 1]],
            ['name' => 'Sophie Martin', 'email' => 'sophie@email.com', 'city' => 'Miami',        'days' => 15, 'items' => [0, 4]],
            ['name' => 'David Kim',     'email' => 'david@email.com',  'city' => 'Seattle',      'days' => 18, 'items' => [2]],
            ['name' => 'Layla Hassan',  'email' => 'layla@email.com',  'city' => 'Dallas',       'days' => 22, 'items' => [9, 5]],
        ];

        foreach ($orderData as $od) {
            $subtotal = 0;
            $costTotal = 0;
            $lineItems = [];

            foreach ($od['items'] as $pi) {
                $p = $productModels[$pi];
                $qty = rand(1, 2);
                $price = (float) $p->effective_price;
                $cost  = (float) $p->cost_price;
                $lineTotal   = round($price * $qty, 2);
                $lineCost    = round($cost  * $qty, 2);
                $lineProfit  = round($lineTotal - $lineCost, 2);
                $subtotal   += $lineTotal;
                $costTotal  += $lineCost;
                $lineItems[] = [
                    'product_id'    => $p->id,
                    'product_name'  => $p->name,
                    'product_sku'   => $p->sku,
                    'product_price' => $price,
                    'product_cost'  => $cost,
                    'quantity'      => $qty,
                    'variant'       => null,
                    'line_total'    => $lineTotal,
                    'line_cost'     => $lineCost,
                    'line_profit'   => $lineProfit,
                    'created_at'    => now()->subDays($od['days']),
                    'updated_at'    => now()->subDays($od['days']),
                ];
            }

            $shipping = $subtotal >= 150 ? 0 : 9.00;
            $total    = $subtotal + $shipping;

            $order = Order::create([
                'order_number'    => Order::generateOrderNumber(),
                'customer_name'   => $od['name'],
                'customer_email'  => $od['email'],
                'shipping_address'=> '123 Main St',
                'shipping_city'   => $od['city'],
                'subtotal'        => $subtotal,
                'shipping_cost'   => $shipping,
                'discount'        => 0,
                'total'           => $total,
                'cost_total'      => $costTotal,
                'status'          => 'delivered',
                'payment_status'  => 'paid',
                'created_at'      => now()->subDays($od['days']),
                'updated_at'      => now()->subDays($od['days']),
            ]);

            foreach ($lineItems as $li) {
                $li['order_id'] = $order->id;
                OrderItem::create($li);
            }
        }

        // ── Contact Messages ─────────────────────────────────────────
        ContactMessage::insert([
            [
                'name'       => 'Emma Wilson',
                'email'      => 'emma@email.com',
                'subject'    => 'Order inquiry',
                'message'    => 'Hi, I placed an order 3 days ago and haven\'t received a tracking number.',
                'is_read'    => false,
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ],
            [
                'name'       => 'Carlos Diaz',
                'email'      => 'carlos@email.com',
                'subject'    => 'Size question',
                'message'    => 'Do the joggers run true to size? I\'m usually between M and L.',
                'is_read'    => true,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
        ]);
    }
}