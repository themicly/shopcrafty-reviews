<?php

namespace Themicly\Shopcrafty\Reviews\Livewire;

use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;
use Themicly\Shopcrafty\Modules\Catalog\Models\Product;
use Themicly\Shopcrafty\Reviews\Models\ProductReview;
use Themicly\Shopcrafty\Reviews\Services\ReviewService;

class ProductReviews extends Component
{
    public int $productId;
    public int $rating = 5;
    public string $authorName = '';
    public string $title = '';
    public string $body = '';

    public function mount(int $productId): void
    {
        $this->productId = $productId;
        $this->authorName = (string) auth('customer')->user()?->name;
    }

    public function submit(ReviewService $reviews): void
    {
        $data = $this->validate(['rating' => ['required', 'integer', 'min:1', 'max:5'], 'authorName' => ['required', 'string', 'max:80'], 'title' => ['nullable', 'string', 'max:120'], 'body' => ['required', 'string', 'max:2000']]);
        $customer = auth('customer')->user();
        if ($customer && ProductReview::where('product_id', $this->productId)->where('customer_id', $customer->id)->exists()) { $this->addError('body', 'You have already reviewed this product.'); return; }
        $key = 'review:'.($customer?->id ?? request()->ip()).':'.$this->productId;
        if (RateLimiter::tooManyAttempts($key, 3)) { $this->addError('body', 'Too many review attempts. Please try again later.'); return; }
        RateLimiter::hit($key, 3600);
        $review = $reviews->submit(Product::findOrFail($this->productId), ['rating' => $data['rating'], 'author_name' => $data['authorName'], 'title' => $data['title'] ?: null, 'body' => $data['body']], $customer);
        $this->reset('title', 'body'); $this->rating = 5;
        $this->dispatch('toast', message: $review->status === 'approved' ? 'Thanks for your review!' : 'Thanks! Your review is awaiting approval.', type: 'success');
    }

    public function render()
    {
        $product = Product::findOrFail($this->productId);
        return view('reviews::livewire.product-reviews', ['product' => $product, 'reviews' => ProductReview::approved()->where('product_id', $product->id)->latest()->limit(20)->get()]);
    }
}
