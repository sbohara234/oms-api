<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('description');
            $table->integer('quantity');
            $table->string('product_code')->nullable();
            $table->string('product_unit')->nullable();
            $table->decimal('b2c_price_per_unit', 10, 2);
            $table->decimal('b2b_price_per_unit', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->date('manufacturing_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'product_code']);
            $table->index(['tenant_id', 'is_active']);
            $table->unique(['product_code', 'product_unit','expiry_date','is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
