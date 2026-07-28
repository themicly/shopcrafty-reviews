<?php

namespace Themicly\Shopcrafty\Reviews\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Themicly\Shopcrafty\Modules\Catalog\Models\Product;

class ProductReview extends Model
{
    protected $table = 'product_reviews';

    protected $fillable = ['product_id', 'customer_id', 'order_id', 'author_name', 'rating', 'title', 'body', 'status', 'verified_purchase'];

    protected function casts(): array
    {
        return ['rating' => 'integer', 'verified_purchase' => 'boolean'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }
}
