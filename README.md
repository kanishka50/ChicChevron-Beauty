<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).



```
```
chicchevron-beauty
├─ .editorconfig
├─ app
│  ├─ Console
│  │  └─ Commands
│  │     └─ MigrateToVariantOnly.php
│  ├─ Exports
│  │  ├─ CustomerReportExport.php
│  │  ├─ InventoryReportExport.php
│  │  └─ SalesReportExport.php
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ Admin
│  │  │  │  ├─ AdminAuthController.php
│  │  │  │  ├─ AdminDashboardController.php
│  │  │  │  ├─ BannerController.php
│  │  │  │  ├─ BrandController.php
│  │  │  │  ├─ CategoryController.php
│  │  │  │  ├─ ColorController.php
│  │  │  │  ├─ ComplaintController.php
│  │  │  │  ├─ InventoryController.php
│  │  │  │  ├─ OrderController.php
│  │  │  │  ├─ ProductController.php
│  │  │  │  ├─ ProductVariantController.php
│  │  │  │  ├─ ReportController.php
│  │  │  │  └─ TextureController.php
│  │  │  ├─ Auth
│  │  │  │  ├─ ForgotPasswordController.php
│  │  │  │  ├─ LoginController.php
│  │  │  │  ├─ RegisterController.php
│  │  │  │  ├─ ResetPasswordController.php
│  │  │  │  └─ VerificationController.php
│  │  │  ├─ CartController.php
│  │  │  ├─ CheckoutController.php
│  │  │  ├─ ComplaintController.php
│  │  │  ├─ Controller.php
│  │  │  ├─ HomeController.php
│  │  │  ├─ OrderController.php
│  │  │  ├─ PaymentController.php
│  │  │  ├─ ProductController.php
│  │  │  ├─ ReviewController.php
│  │  │  ├─ SearchController.php
│  │  │  ├─ UserAccountController.php
│  │  │  └─ WishlistController.php
│  │  ├─ Middleware
│  │  │  ├─ AdminAuth.php
│  │  │  ├─ EnsureEmailIsVerified.php
│  │  │  └─ GuestOrAuth.php
│  │  └─ Requests
│  │     ├─ Admin
│  │     │  ├─ BannerRequest.php
│  │     │  ├─ BrandRequest.php
│  │     │  ├─ CategoryRequest.php
│  │     │  ├─ InventoryRequest.php
│  │     │  ├─ OrderUpdateRequest.php
│  │     │  ├─ ProductRequest.php
│  │     │  └─ ProductVariantRequest.php
│  │     ├─ Auth
│  │     │  ├─ LoginRequest.php
│  │     │  ├─ RegisterRequest.php
│  │     │  └─ ResetPasswordRequest.php
│  │     ├─ CheckoutRequest.php
│  │     ├─ ComplaintRequest.php
│  │     └─ ReviewRequest.php
│  ├─ Mail
│  │  ├─ OrderConfirmation.php
│  │  ├─ OrderStatusUpdate.php
│  │  └─ WelcomeMail.php
│  ├─ Models
│  │  ├─ ActivityLog.php
│  │  ├─ Admin.php
│  │  ├─ Banner.php
│  │  ├─ Brand.php
│  │  ├─ CartItem.php
│  │  ├─ Category.php
│  │  ├─ Color.php
│  │  ├─ Complaint.php
│  │  ├─ ComplaintResponse.php
│  │  ├─ EmailLog.php
│  │  ├─ Faq.php
│  │  ├─ Inventory.php
│  │  ├─ InventoryMovement.php
│  │  ├─ MainCategory.php
│  │  ├─ Order.php
│  │  ├─ OrderItem.php
│  │  ├─ OrderStatusHistory.php
│  │  ├─ Page.php
│  │  ├─ PaymentMethod.php
│  │  ├─ Product.php
│  │  ├─ ProductColor.php
│  │  ├─ ProductImage.php
│  │  ├─ ProductIngredient.php
│  │  ├─ ProductType.php
│  │  ├─ ProductVariant.php
│  │  ├─ Promotion.php
│  │  ├─ PromotionProduct.php
│  │  ├─ PromotionUsage.php
│  │  ├─ Review.php
│  │  ├─ ShippingMethod.php
│  │  ├─ Texture.php
│  │  ├─ User.php
│  │  ├─ UserAddress.php
│  │  └─ Wishlist.php
│  ├─ Notifications
│  │  ├─ ResetPassword.php
│  │  └─ VerifyEmail.php
│  ├─ Policies
│  │  └─ OrderPolicy.php
│  ├─ Providers
│  │  ├─ AppServiceProvider.php
│  │  └─ RouteServiceProvider.php
│  ├─ Services
│  │  ├─ CartService.php
│  │  ├─ InventoryService.php
│  │  ├─ InvoiceService.php
│  │  ├─ OrderService.php
│  │  ├─ PayHereService.php
│  │  ├─ PaymentService.php
│  │  ├─ ProductVariantService.php
│  │  └─ ReportService.php
│  ├─ Traits
│  │  ├─ HasSlug.php
│  │  ├─ LogsActivity.php
│  │  └─ ManagesInventory.php
│  └─ View
│     └─ Components
│        ├─ Admin
│        │  ├─ Card.php
│        │  ├─ Modal.php
│        │  └─ Table.php
│        ├─ OrderStatusBadge.php
│        └─ Shop
│           └─ ProductVariantSelector.php
├─ artisan
├─ bootstrap
│  ├─ app.php
│  ├─ cache
│  │  ├─ packages.php
│  │  └─ services.php
│  └─ providers.php
├─ composer.json
├─ composer.lock
├─ config
│  ├─ app.php
│  ├─ auth.php
│  ├─ cache.php
│  ├─ database.php
│  ├─ dompdf.php
│  ├─ excel.php
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ payhere.php
│  ├─ queue.php
│  ├─ services.php
│  ├─ session.php
│  └─ shop.php
├─ database
│  ├─ factories
│  │  ├─ BrandFactory.php
│  │  ├─ CategoryFactory.php
│  │  ├─ ProductFactory.php
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  ├─ 2025_05_30_084000_create_product_types_table.php
│  │  ├─ 2025_05_30_084001_create_textures_table.php
│  │  ├─ 2025_05_30_084002_create_colors_table.php
│  │  ├─ 2025_05_30_084812_create_user_addresses_table.php
│  │  ├─ 2025_05_30_084855_create_categories_table.php
│  │  ├─ 2025_05_30_084928_create_brands_table.php
│  │  ├─ 2025_05_30_085032_create_products_table.php
│  │  ├─ 2025_05_30_090510_create_product_images_table.php
│  │  ├─ 2025_05_30_090521_create_product_ingredients_table.php
│  │  ├─ 2025_05_30_090530_create_product_colors_table.php
│  │  ├─ 2025_05_30_090538_create_product_variants_table.php
│  │  ├─ 2025_05_30_090546_create_variant_combinations_table.php
│  │  ├─ 2025_05_30_090554_create_inventory_table.php
│  │  ├─ 2025_05_30_090610_create_inventory_movements_table.php
│  │  ├─ 2025_05_30_090615_create_orders_table.php
│  │  ├─ 2025_05_30_090616_create_admins_table.php
│  │  ├─ 2025_05_30_090618_create_order_items_table.php
│  │  ├─ 2025_05_30_090626_create_order_status_history_table.php
│  │  ├─ 2025_05_30_090630_create_promotions_table.php
│  │  ├─ 2025_05_30_090633_create_promotion_products_table.php
│  │  ├─ 2025_05_30_090640_create_promotion_usage_table.php
│  │  ├─ 2025_05_30_090647_create_wishlists_table.php
│  │  ├─ 2025_05_30_090655_create_cart_items_table.php
│  │  ├─ 2025_05_30_090703_create_reviews_table.php
│  │  ├─ 2025_05_30_090710_create_complaints_table.php
│  │  ├─ 2025_05_30_090713_create_complaint_responses_table.php
│  │  ├─ 2025_05_30_090719_create_activity_logs_table.php
│  │  ├─ 2025_05_30_090720_create_banners_table.php
│  │  ├─ 2025_05_30_090721_create_faqs_table.php
│  │  ├─ 2025_05_30_090725_create_email_logs_table.php
│  │  ├─ 2025_05_30_092153_create_pages_table.php
│  │  ├─ 2025_05_30_171914_create_password_reset_tokens_table.php
│  │  ├─ 2025_07_10_072432_add_unit_price_to_cart_items_table.php
│  │  ├─ 2025_07_10_172124_add_customer_email_to_orders_table.php
│  │  ├─ 2025_07_10_172156_add_is_active_to_user_addresses_table.php
│  │  ├─ 2025_07_13_081912_add_rating_columns_to_products_table.php
│  │  ├─ 2025_07_14_040011_add_discount_price_to_variant_combinations_table.php
│  │  └─ 2025_07_15_071731_clean_variant_system_migration.php
│  └─ seeders
│     ├─ AdminSeeder.php
│     ├─ BrandSeeder.php
│     ├─ CategorySeeder.php
│     ├─ ColorSeeder.php
│     ├─ DatabaseSeeder.php
│     ├─ PageSeeder.php
│     ├─ ProductSeeder.php
│     ├─ ProductTypeSeeder.php
│     └─ TextureSeeder.php
├─ package.json
├─ phpunit.xml
├─ postcss.config.js
├─ public
│  ├─ .htaccess
│  ├─ favicon.ico
│  ├─ index.php
│  └─ robots.txt
├─ README.md
├─ resources
│  ├─ css
│  │  └─ app.css
│  ├─ js
│  │  ├─ admin
│  │  │  ├─ app.js
│  │  │  ├─ dashboard.js
│  │  │  └─ reports.js
│  │  ├─ app.js
│  │  ├─ bootstrap.js
│  │  └─ shop
│  │     └─ cart.js
│  └─ views
│     ├─ admin
│     │  ├─ auth
│     │  ├─ banners
│     │  │  ├─ create.blade.php
│     │  │  ├─ edit.blade.php
│     │  │  └─ index.blade.php
│     │  ├─ brands
│     │  │  ├─ create.blade.php
│     │  │  ├─ edit.blade.php
│     │  │  └─ index.blade.php
│     │  ├─ categories
│     │  │  ├─ create.blade.php
│     │  │  ├─ edit.blade.php
│     │  │  └─ index.blade.php
│     │  ├─ colors
│     │  │  └─ index.blade.php
│     │  ├─ complaints
│     │  │  ├─ index.blade.php
│     │  │  └─ show.blade.php
│     │  ├─ dashboard
│     │  │  └─ index.blade.php
│     │  ├─ inventory
│     │  │  ├─ index.blade.php
│     │  │  ├─ movements.blade.php
│     │  │  └─ partials
│     │  │     ├─ add-stock-modal.blade.php
│     │  │     ├─ adjust-stock-modal.blade.php
│     │  │     ├─ inventory-scripts.blade.php
│     │  │     └─ stock-details-modal.blade.php
│     │  ├─ layouts
│     │  │  ├─ app.blade.php
│     │  │  ├─ navigation.blade.php
│     │  │  └─ sidebar.blade.php
│     │  ├─ orders
│     │  │  ├─ index.blade.php
│     │  │  ├─ invoice.blade.php
│     │  │  └─ show.blade.php
│     │  ├─ products
│     │  │  ├─ create.blade.php
│     │  │  ├─ edit.blade.php
│     │  │  ├─ index.blade.php
│     │  │  ├─ partials
│     │  │  │  ├─ create-attributes.blade.php
│     │  │  │  ├─ edit-attributes.blade.php
│     │  │  │  ├─ image-upload.blade.php
│     │  │  │  ├─ ingredients-form.blade.php
│     │  │  │  ├─ stock-indicators.blade.php
│     │  │  │  ├─ variant-form.blade.php
│     │  │  │  └─ variant-scripts.blade.php
│     │  │  ├─ show.blade.php
│     │  │  └─ variants
│     │  │     ├─ create.blade.php
│     │  │     ├─ edit.blade.php
│     │  │     └─ index.blade.php
│     │  ├─ reports
│     │  │  ├─ customers.blade.php
│     │  │  ├─ index.blade.php
│     │  │  ├─ inventory.blade.php
│     │  │  └─ sales.blade.php
│     │  └─ textures
│     │     └─ index.blade.php
│     ├─ auth
│     │  ├─ forgot-password.blade.php
│     │  ├─ login.blade.php
│     │  ├─ register.blade.php
│     │  ├─ reset-password.blade.php
│     │  └─ verify-email.blade.php
│     ├─ cart
│     │  └─ index.blade.php
│     ├─ checkout
│     │  ├─ index.blade.php
│     │  ├─ payment-processing.blade.php
│     │  ├─ payment-success.blade.php
│     │  ├─ payment.blade.php
│     │  └─ success.blade.php
│     ├─ colors
│     │  └─ index.blade.php
│     ├─ components
│     │  ├─ admin
│     │  │  ├─ card.blade.php
│     │  │  ├─ modal.blade.php
│     │  │  └─ table.blade.php
│     │  ├─ alert.blade.php
│     │  ├─ input-error.blade.php
│     │  ├─ input-label.blade.php
│     │  ├─ order-status-badge.blade.php
│     │  ├─ primary-button.blade.php
│     │  ├─ secondary-button.blade.php
│     │  ├─ shop
│     │  │  ├─ cart-dropdown.blade.php
│     │  │  ├─ product-card.blade.php
│     │  │  └─ variant-selector.blade.php
│     │  └─ text-input.blade.php
│     ├─ emails
│     │  ├─ order-confirmation.blade.php
│     │  ├─ order-status.blade.php
│     │  ├─ password-reset.blade.php
│     │  ├─ verify-email.blade.php
│     │  └─ welcome.blade.php
│     ├─ errors
│     │  ├─ 404.blade.php
│     │  ├─ 500.blade.php
│     │  └─ 503.blade.php
│     ├─ home
│     │  ├─ banner-slider.blade.php
│     │  └─ index.blade.php
│     ├─ layouts
│     │  ├─ app.blade.php
│     │  ├─ footer.blade.php
│     │  ├─ guest.blade.php
│     │  └─ navigation.blade.php
│     ├─ products
│     │  ├─ index.blade.php
│     │  └─ show.blade.php
│     ├─ user
│     │  ├─ account
│     │  │  ├─ address-create.blade.php
│     │  │  ├─ address-edit.blade.php
│     │  │  ├─ addresses.blade.php
│     │  │  ├─ index.blade.php
│     │  │  ├─ profile.blade.php
│     │  │  └─ security.blade.php
│     │  ├─ complaints
│     │  │  ├─ create.blade.php
│     │  │  ├─ index.blade.php
│     │  │  └─ show.blade.php
│     │  ├─ orders
│     │  │  ├─ index.blade.php
│     │  │  └─ show.blade.php
│     │  ├─ reviews
│     │  │  ├─ create-single.blade.php
│     │  │  ├─ create.blade.php
│     │  │  └─ index.blade.php
│     │  └─ wishlist
│     │     └─ index.blade.php
│     └─ welcome.blade.php
├─ routes
│  ├─ admin.php
│  ├─ console.php
│  └─ web.php
├─ storage
│  └─ framework
├─ tailwind.config.js
├─ tests
│  ├─ Feature
│  │  └─ ExampleTest.php
│  ├─ TestCase.php
│  └─ Unit
│     └─ ExampleTest.php
└─ vite.config.js

```