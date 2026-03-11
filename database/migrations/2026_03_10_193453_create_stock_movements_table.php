<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // 'in'  = stock added  (purchase, return, manual adjustment up)
            // 'out' = stock removed (sale, damage, manual adjustment down)
            $table->enum('type', ['in', 'out', 'adjustment']);

            $table->integer('quantity');          // always positive — type defines direction
            $table->integer('stock_before');      // snapshot for audit trail
            $table->integer('stock_after');       // snapshot for audit trail

            // What caused this movement
            $table->string('reference_type')->nullable(); // 'purchase_order' | 'order' | 'manual'
            $table->unsignedBigInteger('reference_id')->nullable(); // the PO or Order id

            $table->decimal('unit_cost', 10, 2)->nullable(); // cost at time of movement (for 'in')
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};