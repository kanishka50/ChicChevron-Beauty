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
chicchevron-beauty
├─ .editorconfig
├─ app
│  ├─ Http
│  │  ├─ Controllers
│  │  │  ├─ Admin
│  │  │  │  └─ AdminAuthController.php
│  │  │  ├─ Auth
│  │  │  │  ├─ ForgotPasswordController.php
│  │  │  │  ├─ LoginController.php
│  │  │  │  ├─ RegisterController.php
│  │  │  │  ├─ ResetPasswordController.php
│  │  │  │  └─ VerificationController.php
│  │  │  └─ Controller.php
│  │  ├─ Middleware
│  │  │  ├─ AdminAuth.php
│  │  │  ├─ EnsureEmailIsVerified.php
│  │  │  └─ GuestOrAuth.php
│  │  └─ Requests
│  │     └─ Auth
│  │        ├─ LoginRequest.php
│  │        ├─ RegisterRequest.php
│  │        └─ ResetPasswordRequest.php
│  ├─ Mail
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
│  │  ├─ Order.php
│  │  ├─ OrderItem.php
│  │  ├─ OrderStatusHistory.php
│  │  ├─ Page.php
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
│  │  ├─ Texture.php
│  │  ├─ User.php
│  │  ├─ UserAddress.php
│  │  ├─ VariantCombination.php
│  │  └─ Wishlist.php
│  ├─ Providers
│  │  ├─ AppServiceProvider.php
│  │  └─ RouteServiceProvider.php
│  ├─ Traits
│  │  ├─ HasSlug.php
│  │  ├─ LogsActivity.php
│  │  └─ ManagesInventory.php
│  └─ View
│     └─ Components
│        └─ Admin
│           ├─ Card.php
│           ├─ Modal.php
│           └─ Table.php
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
│  ├─ filesystems.php
│  ├─ logging.php
│  ├─ mail.php
│  ├─ payhere.php
│  ├─ queue.php
│  ├─ services.php
│  ├─ session.php
│  └─ shop.php
├─ database
│  ├─ database.sqlite
│  ├─ factories
│  │  ├─ BrandFactory.php
│  │  ├─ CategoryFactory.php
│  │  ├─ ProductFactory.php
│  │  └─ UserFactory.php
│  ├─ migrations
│  │  ├─ 0001_01_01_000000_create_users_table.php
│  │  ├─ 0001_01_01_000001_create_cache_table.php
│  │  ├─ 0001_01_01_000002_create_jobs_table.php
│  │  ├─ 2025_05_30_084812_create_user_addresses_table.php
│  │  ├─ 2025_05_30_084855_create_categories_table.php
│  │  ├─ 2025_05_30_084928_create_brands_table.php
│  │  ├─ 2025_05_30_085032_create_products_table.php
│  │  ├─ 2025_05_30_090444_create_product_types_table.php
│  │  ├─ 2025_05_30_090452_create_textures_table.php
│  │  ├─ 2025_05_30_090500_create_colors_table.php
│  │  ├─ 2025_05_30_090510_create_product_images_table.php
│  │  ├─ 2025_05_30_090521_create_product_ingredients_table.php
│  │  ├─ 2025_05_30_090530_create_product_colors_table.php
│  │  ├─ 2025_05_30_090538_create_product_variants_table.php
│  │  ├─ 2025_05_30_090546_create_variant_combinations_table.php
│  │  ├─ 2025_05_30_090554_create_inventory_table.php
│  │  ├─ 2025_05_30_090610_create_inventory_movements_table.php
│  │  ├─ 2025_05_30_090618_create_order_items_table.php
│  │  ├─ 2025_05_30_090626_create_order_status_history_table.php
│  │  ├─ 2025_05_30_090633_create_promotion_products_table.php
│  │  ├─ 2025_05_30_090640_create_promotion_usage_table.php
│  │  ├─ 2025_05_30_090647_create_wishlists_table.php
│  │  ├─ 2025_05_30_090655_create_cart_items_table.php
│  │  ├─ 2025_05_30_090703_create_reviews_table.php
│  │  ├─ 2025_05_30_090713_create_complaint_responses_table.php
│  │  ├─ 2025_05_30_090719_create_activity_logs_table.php
│  │  ├─ 2025_05_30_090725_create_email_logs_table.php
│  │  ├─ 2025_05_30_091617_create_admins_table.php
│  │  ├─ 2025_05_30_092153_create_pages_table.php
│  │  └─ 2025_05_30_171914_create_password_reset_tokens_table.php
│  └─ seeders
│     ├─ AdminSeeder.php
│     ├─ BrandSeeder.php
│     ├─ CategorySeeder.php
│     ├─ ColorSeeder.php
│     ├─ DatabaseSeeder.php
│     ├─ PageSeeder.php
│     ├─ ProductTypeSeeder.php
│     └─ TextureSeeder.php
├─ package-lock.json
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
│  │  │  └─ app.js
│  │  ├─ app.js
│  │  └─ bootstrap.js
│  └─ views
│     ├─ admin
│     │  ├─ auth
│     │  │  └─ login.blade.php
│     │  ├─ dashboard
│     │  │  └─ index.blade.php
│     │  └─ layouts
│     │     ├─ app.blade.php
│     │     ├─ navigation.blade.php
│     │     └─ sidebar.blade.php
│     ├─ auth
│     │  ├─ forgot-password.blade.php
│     │  ├─ login.blade.php
│     │  ├─ register.blade.php
│     │  ├─ reset-password.blade.php
│     │  └─ verify-email.blade.php
│     ├─ components
│     │  ├─ admin
│     │  │  ├─ card.blade.php
│     │  │  ├─ modal.blade.php
│     │  │  └─ table.blade.php
│     │  ├─ alert.blade.php
│     │  ├─ input-error.blade.php
│     │  ├─ input-label.blade.php
│     │  ├─ primary-button.blade.php
│     │  ├─ secondary-button.blade.php
│     │  └─ text-input.blade.php
│     ├─ emails
│     │  └─ welcome.blade.php
│     ├─ errors
│     │  ├─ 404.blade.php
│     │  ├─ 500.blade.php
│     │  └─ 503.blade.php
│     ├─ layouts
│     │  ├─ app.blade.php
│     │  ├─ footer.blade.php
│     │  ├─ guest.blade.php
│     │  └─ navigation.blade.php
│     └─ welcome.blade.php
├─ routes
│  ├─ admin.php
│  ├─ console.php
│  └─ web.php
├─ storage
│  ├─ app
│  │  ├─ private
│  │  └─ public
│  │     ├─ banners
│  │     ├─ brands
│  │     ├─ categories
│  │     └─ products
│  ├─ framework
│  │  ├─ cache
│  │  │  └─ data
│  │  ├─ sessions
│  │  │  ├─ 8uTKWWEOBhqTc6Qfno1RMFPwscA0M8MxYFOBDcPe
│  │  │  ├─ 95LYLlt4v7Xb3dgFyN0XY7r4i5lMjXiWfodJeso2
│  │  │  ├─ TLiCH1Dxpz5f2c2HPEDJi6ePrBUbeuhvhZtdWOgI
│  │  │  └─ ZkyF5p95YinFsbzrP384AismzCTbNEhDtnmvsDJM
│  │  ├─ testing
│  │  └─ views
│  │     ├─ 02097000b2a9d6e8bc560575383d0f91.php
│  │     ├─ 026419a83c4b57301f816523a0d181c3.php
│  │     ├─ 02d52fcb8486925b5f04659bcb64dc95.php
│  │     ├─ 0a7245d0aa8271f04e66e9c6b5ca4fbf.php
│  │     ├─ 17fff6e597d2c1f8e125078bc4467bbc.php
│  │     ├─ 1c36cf04197d149e1fee529072af132d.php
│  │     ├─ 1ce62dd79ad4e233a55945d284ea59be.php
│  │     ├─ 1cedcc34bb8727f82956a9bc217f6400.php
│  │     ├─ 1d8c777ceb845cba32dd081eaf314625.php
│  │     ├─ 1fc83f1771c0fe25bb433de17bbeb775.php
│  │     ├─ 2383baa099758ef2667cfa4865a64a7a.php
│  │     ├─ 262bb6226dc1540398bb5049f5c38760.php
│  │     ├─ 2812a292b4d78c65830370f2ece13768.php
│  │     ├─ 302be44af181fdb55676cfbf4e84bc40.php
│  │     ├─ 39bc060b04d07d3030bd57d52c711bc1.php
│  │     ├─ 3b08aa323537ca06e873e471477fa766.php
│  │     ├─ 4299808e82bcb111a8f7d98d386f533f.php
│  │     ├─ 4a60a99dba9b2fd2d19918cb32cee02b.php
│  │     ├─ 4ae20849f1e604e4e60155789684ceab.php
│  │     ├─ 4b9f7e317ba2b31d75db52aabdfeae32.php
│  │     ├─ 4e0fd7e1c496fa9c5e5de70c71b08534.php
│  │     ├─ 5120d53e0fb046d561bf40a086a77b49.php
│  │     ├─ 5497cd7c56dbc0bc6fcd6cf5fe9718ed.php
│  │     ├─ 5d1db28a671098ccdb8dd8d169d62c70.php
│  │     ├─ 660d00f49f4717e2939f9615fa2df1a4.php
│  │     ├─ 68d9550d6aecf5cac099ba2bec2239ec.php
│  │     ├─ 6ffe50eb49ac34f15e8a0d9cbd40c8ac.php
│  │     ├─ 7673981fd3fdacc7a1acd535a002c33f.php
│  │     ├─ 9098bbd40cc52455737a5394396dd38c.php
│  │     ├─ 91de65cd345d0796e76b1b37cdb42be2.php
│  │     ├─ 9c64735f3bde91f8e18c06a08517aded.php
│  │     ├─ a6fe1ebb0c7f0e5b24a78d89eb23347d.php
│  │     ├─ aa1999ee9e3b5b5b4ec7de121d6cd8e2.php
│  │     ├─ ae1413591198bbfe795d8c47893e194d.php
│  │     ├─ b0cdb8ac5a091bb3575cb25de2e05df2.php
│  │     ├─ c1c1f09926c5f0bd50bd7266978523cd.php
│  │     ├─ c79b12c1982b43aa04f3ba19a3d6a8a3.php
│  │     ├─ dcae0ff1c633f9ea4ebc791aeffe62d8.php
│  │     ├─ de2b2c05871f09630d22029ad17f9245.php
│  │     ├─ e2b1dd266e22f62061e1a6e0da78ade2.php
│  │     ├─ f47cbfa9974571d95055babb77b4aba6.php
│  │     ├─ f8a29ef4c7f1921745945e20d617966d.php
│  │     └─ fba93916fdccafc3a5b98f47378cde6f.php
│  └─ logs
├─ tailwind.config.js
├─ tests
│  ├─ Feature
│  │  └─ ExampleTest.php
│  ├─ TestCase.php
│  └─ Unit
│     └─ ExampleTest.php
└─ vite.config.js

```