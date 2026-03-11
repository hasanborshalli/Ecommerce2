<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Purchase order headers ──────────────────────────────────────
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference_number')->unique(); // e.g. PO-2024-001
            $table->date('order_date');
            $table->date('received_date')->nullable();
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->enum('status', ['draft', 'ordered', 'received', 'partial'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ── Purchase order line items ───────────────────────────────────
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->integer('quantity_ordered');
            $table->integer('quantity_received')->default(0);
            $table->decimal('cost_per_unit', 10, 2);  // cost paid for this batch
            $table->decimal('total_cost', 10, 2);      // quantity_ordered × cost_per_unit
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
    }
};