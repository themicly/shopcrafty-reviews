<?php

namespace Themicly\Shopcrafty\Reviews\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Themicly\Shopcrafty\Reviews\Models\ProductReview;
use Themicly\Shopcrafty\Reviews\Services\ReviewService;

class ReviewModeration extends Component
{
    use WithPagination;
    public string $status = 'pending';

    public function approve(int $id, ReviewService $service): void { $service->setStatus(ProductReview::findOrFail($id), 'approved'); }
    public function reject(int $id, ReviewService $service): void { $service->setStatus(ProductReview::findOrFail($id), 'rejected'); }
    public function delete(int $id, ReviewService $service): void { $service->delete(ProductReview::findOrFail($id)); }

    public function render()
    {
        $query = ProductReview::with('product')->latest();
        if ($this->status !== 'all') $query->where('status', $this->status);
        return view('reviews::livewire.review-moderation', ['reviews' => $query->paginate(20)]);
    }
}
