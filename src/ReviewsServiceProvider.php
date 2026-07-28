<?php

namespace Themicly\Shopcrafty\Reviews;

use Themicly\Shopcrafty\Core\Module\ModuleServiceProvider;
use Themicly\Shopcrafty\Reviews\Models\ProductReview;
use Themicly\Shopcrafty\Reviews\Services\ReviewService;

final class ReviewsServiceProvider extends ModuleServiceProvider
{
    protected function moduleName(): string
    {
        return 'Reviews';
    }

    protected function modulePath(): string
    {
        return __DIR__;
    }

    protected function bootModule(): void
    {
        $this->addonRegistry()->register('reviews', [
            'name' => 'Product reviews and moderation',
            'description' => 'Collect ratings and moderate customer reviews.',
            'settings_route' => 'admin.themes.settings',
            'provider' => self::class,
            'review_model' => ProductReview::class,
            'review_service' => ReviewService::class,
        ]);
        $this->addonRegistry()->registerStorefrontFeature('product', 'reviews', [
            'label' => 'Reviews',
            'route' => 'storefront.product',
        ]);
        $this->addonRegistry()->registerSettingsSchema('reviews', [
            'label' => 'Reviews settings',
            'fields' => ['catalog.reviews_enabled'],
        ]);
    }
}
