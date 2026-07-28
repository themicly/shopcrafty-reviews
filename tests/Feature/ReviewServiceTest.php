<?php

namespace Themicly\Shopcrafty\Reviews\Tests\Feature;

use Themicly\Shopcrafty\Modules\Catalog\Models\Product;
use Themicly\Shopcrafty\Core\Module\AddonRegistry;
use Themicly\Shopcrafty\Reviews\Models\ProductReview;
use Themicly\Shopcrafty\Reviews\Services\ReviewService;
use Themicly\Shopcrafty\Reviews\Tests\TestCase;

final class ReviewServiceTest extends TestCase
{
    public function test_addon_registers_model_and_service(): void
    {
        $addons = app(AddonRegistry::class)->all()['reviews'] ?? [];
        $this->assertSame(ProductReview::class, $addons['review_model'] ?? null);
        $this->assertSame(ReviewService::class, $addons['review_service'] ?? null);
        $this->assertTrue(route('admin.catalog.reviews.index') !== '');
    }

    public function test_reviews_start_pending_and_approved_aggregates_are_recalculated(): void
    {
        $this->artisan('migrate');
        $product = Product::create(['name' => 'Desk lamp', 'price' => 1200, 'status' => 'active']);
        $service = app(ReviewService::class);
        $review = $service->submit($product, ['rating' => 5, 'author_name' => 'Sam', 'body' => 'Excellent lamp.']);

        $this->assertSame('pending', $review->status);
        $this->assertSame(0, $product->fresh()->reviews_count);

        $service->setStatus($review, 'approved');
        $this->assertSame(1, $product->fresh()->reviews_count);
        $this->assertSame(5.0, (float) $product->fresh()->reviews_avg);
    }
}
