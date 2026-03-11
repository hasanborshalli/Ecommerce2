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
            ['name' => 'Apparel',      'slug' => 'apparel',      'sort_order' => 1],
            ['name' => 'Footwear',     'slug' => 'footwear',     'sort_order' => 2],
            ['name' => 'Accessories',  'slug' => 'accessories',  'sort_order' => 3],
            ['name' => 'Tech Gear',    'slug' => 'tech-gear',    'sort_order' => 4],
            ['name' => 'Bags',         'slug' => 'bags',         'sort_order' => 5],
            ['name' => 'New Arrivals', 'slug' => 'new-arrivals', 'sort_order' => 6],
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
            // Apparel
            [
                'category'    => 'apparel',
                'name'        => 'Merino Performance Tee',
                'price'       => 89.00,
                'cost_price'  => 28.00,
                'stock'       => 42,
                'variants'    => ['Size' => ['XS','S','M','L','XL'], 'Color' => ['Navy','White','Slate']],
                'is_featured' => true,
                'is_new'      => true,
            ],
            [
                'category'   => 'apparel',
                'name'       => 'Technical Jogger',
                'price'      => 119.00,
                'cost_price' => 38.00,
                'stock'      => 28,
                'variants'   => ['Size' => ['S','M','L','XL'], 'Color' => ['Black','Olive']],
                'is_featured'=> true,
            ],
            [
                'category'   => 'apparel',
                'name'       => 'Structured Quarter-Zip',
                'price'      => 145.00,
                'cost_price' => 48.00,
                'stock'      => 15,
                'variants'   => ['Size' => ['S','M','L','XL','XXL'], 'Color' => ['Charcoal','Navy']],
                'is_on_sale' => true,
                'sale_price' => 109.00,
            ],
            // Footwear
            [
                'category'    => 'footwear',
                'name'        => 'Trail Runner Pro',
                'price'       => 165.00,
                'cost_price'  => 55.00,
                'stock'       => 20,
                'variants'    => ['Size' => ['40','41','42','43','44','45'], 'Color' => ['Black/Grey','Blue/White']],
                'is_featured' => true,
                'is_new'      => true,
            ],
            [
                'category'   => 'footwear',
                'name'       => 'Everyday Leather Sneaker',
                'price'      => 195.00,
                'cost_price' => 68.00,
                'stock'      => 12,
                'variants'   => ['Size' => ['40','41','42','43','44'], 'Color' => ['White','Tan','Black']],
            ],
            // Accessories
            [
                'category'   => 'accessories',
                'name'       => 'Merino Beanie',
                'price'      => 45.00,
                'cost_price' => 12.00,
                'stock'      => 60,
                'variants'   => ['Color' => ['Black','Navy','Oatmeal','Forest']],
                'is_featured'=> true,
            ],
            [
                'category'   => 'accessories',
                'name'       => 'Leather Card Wallet',
                'price'      => 65.00,
                'cost_price' => 18.00,
                'stock'      => 35,
                'variants'   => ['Color' => ['Black','Tan','Cognac']],
                'is_new'     => true,
            ],
            // Tech Gear
            [
                'category'    => 'tech-gear',
                'name'        => 'Wireless Charging Pad',
                'price'       => 55.00,
                'cost_price'  => 16.00,
                'stock'       => 40,
                'is_featured' => true,
            ],
            [
                'category'   => 'tech-gear',
                'name'       => 'Noise-Cancelling Earbuds',
                'price'      => 189.00,
                'cost_price' => 62.00,
                'stock'      => 18,
                'variants'   => ['Color' => ['Matte Black','Pearl White']],
                'is_new'     => true,
                'is_featured'=> true,
            ],
            // Bags
            [
                'category'   => 'bags',
                'name'       => 'Technical Backpack 25L',
                'price'      => 210.00,
                'cost_price' => 72.00,
                'stock'      => 10,
                'variants'   => ['Color' => ['Black','Navy','Olive']],
                'is_featured'=> true,
            ],
            [
                'category'    => 'bags',
                'name'        => 'Commuter Tote',
                'price'       => 135.00,
                'cost_price'  => 44.00,
                'stock'       => 22,
                'variants'    => ['Color' => ['Black','Sand']],
                'is_new'      => true,
            ],
            [
                'category'   => 'bags',
                'name'       => 'Packable Day Pack',
                'price'      => 79.00,
                'cost_price' => 24.00,
                'stock'      => 3,   // intentionally low stock
                'is_on_sale' => true,
                'sale_price' => 59.00,
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
            ['name' => 'Layla Hassan',  'email' => 'layla@email.com',  'city' => 'Dallas',       'days' => 22, 'items' => [10, 5]],
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