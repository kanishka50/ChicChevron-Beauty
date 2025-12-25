# ChicChevron Beauty - Project Guide

> **Quick Context for Claude**: This is a Laravel e-commerce for beauty products. Admin panel uses Filament v3. Frontend uses Blade templates with Tailwind CSS. COD payments only.

---

## Current Status (December 2024)

| Component | Status |
|-----------|--------|
| Admin Panel (Filament) | **100% Complete** |
| Frontend (Blade + Tailwind) | Functional - **UI Update Needed** |
| Variant System | Complete (attributes + options) |
| Inventory System | Complete (IN/OUT movements) |
| Payment | COD only |

---

## Project Overview

- **Framework**: Laravel 12.x
- **PHP Version**: 8.2+
- **Database**: MySQL (chicchevron_beauty_test)
- **Admin Panel**: Filament v3 (at `/admin`)
- **Frontend**: Blade templates with Tailwind CSS

---

## Filament Admin Credentials

- **URL**: http://127.0.0.1:8000/admin
- **Email**: admin@chicchevron.lk
- **Password**: admin123

---

## Brand Identity

**Logo**: ChicChevron
**Tagline**: "CHIC BY NATURE, CHEVRON BY STYLE"

**Logo Colors** (from Asset 10@4x.png):
- Primary Pink (Light): `#F5E1E4` (soft blush pink)
- Accent Pink (Dark): `#9E6B6B` (dusty rose)
- Text: Light pink tones

---

## Phase: Frontend UI Update

### Goal
Update the customer-facing frontend UI to be elegant, modern, and stylish - matching the ChicChevron brand identity. **No logic or variable changes** - purely visual/styling updates.

### Pages to Update

| Page | Blade File | Priority |
|------|------------|----------|
| **Homepage** | `resources/views/home.blade.php` | High |
| **Header/Navigation** | `resources/views/layouts/navigation.blade.php` | High |
| **Footer** | `resources/views/layouts/footer.blade.php` | High |
| **Product Listing** | `resources/views/products/index.blade.php` | High |
| **Product Detail** | `resources/views/products/show.blade.php` | High |
| **Cart** | `resources/views/cart/index.blade.php` | Medium |
| **Checkout** | `resources/views/checkout/index.blade.php` | Medium |
| **User Account** | `resources/views/account/*.blade.php` | Medium |
| **Auth Pages** | `resources/views/auth/*.blade.php` | Low |
| **Static Pages** | Contact, About, etc. | Low |

### UI Update Rules

1. **NO logic changes** - Only update HTML structure and Tailwind classes
2. **NO variable changes** - Keep all existing `$variables` and Blade directives
3. **NO route changes** - Keep all existing links and form actions
4. **Keep all existing functionality** - Just improve visual appearance
5. **Mobile-first responsive design** - Ensure all updates work on mobile

### Components to Style

- Buttons (primary, secondary, outline)
- Cards (product cards, info cards)
- Forms (inputs, selects, textareas)
- Navigation (header, mobile menu)
- Product grid layouts
- Price displays
- Stock indicators
- Cart summary
- Checkout flow

---

## Database Schema (Reference)

```
products → product_attributes → attribute_options
    ↓
product_variants → variant_attribute_values
    ↓
inventory → inventory_movements
```

---

## Key Models

- `Product` - Main products
- `ProductVariant` - Product variations (SKU, price)
- `ProductAttribute` - Attribute types (size, color, scent)
- `AttributeOption` - Attribute values (50ml, Red, Rose)
- `Inventory` - Stock tracking per variant
- `Order` / `OrderItem` - Customer orders
- `User` - Customers
- `Category` / `Brand` - Product organization

---

*Last Updated: December 2024*
