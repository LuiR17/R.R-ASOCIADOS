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
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->string('badge')->nullable(); // e.g. 'Best Seller', 'Heavy Duty', 'En Stock'
            $table->json('specs')->nullable(); // array of key-value pairs, e.g. {"Presión": "3000 PSI", "Flujo": "20 GPM"}
            $table->string('whatsapp_number')->nullable(); // override custom WA number if needed
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_service')->default(false); // Repair service flag
            $table->timestamps();

            // Indexes for fast search and filter performance
            $table->index('name');
            $table->index('badge');
            $table->index('is_featured');
            $table->index(['is_active', 'is_service']);
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
