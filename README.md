# Shopcrafty Reviews

Moderated product reviews and star ratings for Shopcrafty.

## Requirements

- PHP 8.3+
- Laravel 13+
- `themicly/shopcrafty` 1.0+

## Installation

```bash
composer require themicly/shopcrafty-reviews
php artisan migrate
```

The package is auto-discovered by Laravel. Enable reviews from Admin → Themes
→ Storefront settings.

## Features

- Product review form through `reviews.product-reviews`
- One review per signed-in customer per product
- Session/IP submission throttling
- Pending, approved, and rejected moderation states
- Verified-purchase detection from paid or delivered orders
- Denormalized approved rating count and average on products
- Admin moderation at `/admin/catalog/reviews`

New reviews are pending by default. The service reads the optional
`reviews.auto_approve` setting when deciding whether to publish a review
immediately.

## Theme integration

Addon-owned views use the `reviews::` namespace. Bundled Shopcrafty themes
guard the Livewire component behind addon availability and the
`catalog.reviews_enabled` setting.

## License

MIT. Targets PHP 8.3+ and Laravel 13+.
