<?php

namespace Themicly\Shopcrafty\Reviews\Services;

use Illuminate\Support\Facades\DB;
use Themicly\Shopcrafty\Modules\Catalog\Models\Product;
use Themicly\Shopcrafty\Reviews\Models\ProductReview;

class ReviewService
{
    public function submit(Product $product, array $data, mixed $customer = null): ProductReview
    {
        $verified = $customer && DB::table('order_items')->join('orders', 'orders.id', '=', 'order_items.order_id')->where('orders.customer_id', $customer->id)->where('order_items.product_id', $product->id)->where(fn ($q) => $q->where('orders.payment_status', 'paid')->orWhere('orders.status', 'delivered'))->exists();
        $review = ProductReview::create(['product_id' => $product->id, 'customer_id' => $customer?->id, 'author_name' => $data['author_name'], 'rating' => max(1, min(5, (int) $data['rating'])), 'title' => $data['title'] ?? null, 'body' => $data['body'] ?? null, 'status' => settings('reviews.auto_approve', false) ? 'approved' : 'pending', 'verified_purchase' => $verified]);
        $this->recalculate($product);
        return $review;
    }

    public function setStatus(ProductReview $review, string $status): void
    {
        $review->update(['status' => $status]);
        if ($review->product) $this->recalculate($review->product);
    }

    public function delete(ProductReview $review): void
    {
        $product = $review->product;
        $review->delete();
        if ($product) $this->recalculate($product);
    }

    public function recalculate(Product $product): void
    {
        $stats = ProductReview::approved()->where('product_id', $product->id)->selectRaw('COUNT(*) as c, COALESCE(AVG(rating), 0) as a')->first();
        $product->forceFill(['reviews_count' => (int) $stats->c, 'reviews_avg' => round((float) $stats->a, 2)])->save();
    }
}
