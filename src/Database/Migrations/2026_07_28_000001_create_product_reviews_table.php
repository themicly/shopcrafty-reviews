<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('product_id')->constrained('catalog_products')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->string('author_name');
            $table->unsignedTinyInteger('rating');
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('verified_purchase')->default(false);
            $table->timestamps();
            $table->index(['product_id', 'status']);
        });

        Schema::table('catalog_products', function (Blueprint $table): void {
            $table->decimal('reviews_avg', 3, 2)->default(0)->after('published_at');
            $table->unsignedInteger('reviews_count')->default(0)->after('reviews_avg');
        });
    }

    public function down(): void
    {
        Schema::table('catalog_products', fn (Blueprint $table) => $table->dropColumn(['reviews_avg', 'reviews_count']));
        Schema::dropIfExists('product_reviews');
    }
};
