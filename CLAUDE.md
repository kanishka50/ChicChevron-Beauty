# ChicChevron Beauty - Project Guide

> **Quick Context for Claude**: This is a Laravel e-commerce for beauty products. Admin panel uses Filament v3. Key focus: Structured product variants with attribute/option system and simple inventory tracking. COD payments only.

---

## Current Status (December 2024)

| Component | Status |
|-----------|--------|
| Frontend (Blade) | Complete |
| Admin Panel (Filament) | **95% Complete** - See comparison below |
| Variant System | Structured (attributes + options + auto-generation) |
| Inventory System | Simplified (IN/OUT movements) |
| Payment | COD only |

---

## Filament Admin Panel vs Blade Templates - Feature Comparison

### Implemented in Filament ✅

| Feature | Blade Template | Filament Resource | Status |
|---------|---------------|-------------------|--------|
| **Dashboard** | `admin/dashboard/index.blade.php` | Dashboard + Widgets | ✅ Complete |
| └─ Stats Overview | ✅ | StatsOverview Widget | ✅ |
| └─ Low Stock Alerts | ✅ | LowStockAlert Widget | ✅ |
| └─ Recent Orders | ✅ | RecentOrders Widget | ✅ |
| **Products** | `admin/products/*.blade.php` | ProductResource | ✅ Complete |
| └─ List Products | ✅ | ListProducts Page | ✅ |
| └─ Create Product | ✅ | CreateProduct Page (Tabs + Variants) | ✅ |
| └─ Edit Product | ✅ | EditProduct Page (Tabs + Variants) | ✅ |
| └─ View Product | ✅ | ViewProduct Page | ✅ |
| └─ Variant Management | ✅ | Integrated in Create/Edit | ✅ |
| └─ Attribute Options | ✅ | Integrated in Create/Edit | ✅ |
| **Categories** | `admin/categories/*.blade.php` | CategoryResource | ✅ Complete |
| **Brands** | `admin/brands/*.blade.php` | BrandResource | ✅ Complete |
| **Orders** | `admin/orders/*.blade.php` | OrderResource | ✅ Complete |
| └─ List Orders | ✅ | ListOrders Page | ✅ |
| └─ View Order Details | ✅ | ViewOrder Page (Infolist) | ✅ |
| └─ Edit Order | ✅ | EditOrder Page | ✅ |
| └─ Status Actions (Ship/Complete/Cancel) | ✅ | Table Actions | ✅ |
| └─ Status History Timeline | ✅ | Infolist Section | ✅ |
| **Inventory** | `admin/inventory/*.blade.php` | InventoryResource | ✅ Complete |
| └─ Stock List | ✅ | ListInventories Page | ✅ |
| └─ Add Stock Action | ✅ | Table Action | ✅ |
| └─ Adjust Stock Action | ✅ | Table Action | ✅ |
| └─ Stock Movements | ✅ | InventoryMovementResource | ✅ |
| **Complaints** | `admin/complaints/*.blade.php` | ComplaintResource | ✅ Complete |
| └─ List Complaints | ✅ | ListComplaints Page | ✅ |
| └─ View Complaint | ✅ | ViewComplaint Page (Infolist) | ✅ |
| └─ Status Actions | ✅ | Table Actions + Header Actions | ✅ |
| └─ Send Response | ✅ | Header Action with Form | ✅ |
| └─ Conversation History | ✅ | Infolist Section | ✅ |

### NOT YET Implemented in Filament ❌

| Feature | Blade Template | Priority | Notes |
|---------|---------------|----------|-------|
| **Reports** | `admin/reports/*.blade.php` | Low | Can use Filament's built-in stats |
| └─ Reports Dashboard | ✅ | - | Quick stats overview |
| └─ Sales Reports | ✅ | - | Date filters, charts |
| └─ Inventory Reports | ✅ | - | Stock levels, value |
| └─ Customer Reports | ✅ | - | Demographics, patterns |
| **Invoice Download** | `admin/orders/invoice.blade.php` | Medium | PDF generation for orders |

### Implementation Summary

**Implemented (95%):**
- ✅ Dashboard with widgets (Stats, Low Stock, Recent Orders)
- ✅ Product Management (Create/Edit/View with Variants & Attributes)
- ✅ Category Management
- ✅ Brand Management
- ✅ Order Management (List/View/Edit + Status Actions + History)
- ✅ Inventory Management (Stock levels + Add/Adjust actions)
- ✅ Inventory Movements (Read-only log)
- ✅ Complaint Management (List/View + Status Actions + Responses)

**Missing (5%):**
- ❌ Reports Section (Sales/Inventory/Customer)
- ❌ Invoice PDF Download

---

## Project Overview

**ChicChevron Beauty** is a Laravel-based e-commerce platform for beauty and cosmetics products in Sri Lanka.

- **Framework**: Laravel 12.x
- **PHP Version**: 8.2+
- **Database**: MySQL (chicchevron_beauty_test)
- **Admin Panel**: Filament v3
- **Frontend**: Blade templates with Tailwind CSS

---

## Database Schema

### Complete Schema Diagram

```
┌─────────────┐     ┌────────────────────┐     ┌────────────────────┐
│  products   │────▶│ product_attributes │────▶│ attribute_options  │
└─────────────┘     │ (size, color, etc) │     │ (50ml, Red, etc)   │
      │             └────────────────────┘     └────────────────────┘
      │                                                  │
      ▼                                                  │
┌──────────────────┐     ┌──────────────────────────┐   │
│ product_variants │◀────│ variant_attribute_values │◀──┘
│ (each combo)     │     │ (links variant↔options)  │
└──────────────────┘     └──────────────────────────┘
      │
      ▼
┌─────────────┐     ┌─────────────────────┐
│  inventory  │────▶│ inventory_movements │
│(stock cache)│     │    (IN/OUT log)     │
└─────────────┘     └─────────────────────┘
```

---

## Part 1: Variant Management System

### Table 1: `product_attributes`

**Purpose**: Which attributes a product uses (e.g., "This perfume has Size and Scent")

```sql
product_attributes
├── id
├── product_id              ← FK to products
├── attribute_name          ← 'size', 'color', 'scent', 'shade', 'finish', 'type'
├── display_order           ← For UI ordering (1, 2, 3...)
├── created_at
├── updated_at

UNIQUE (product_id, attribute_name)
INDEX (product_id)
```

### Available Attribute Names

| Attribute | Use Case | Examples |
|-----------|----------|----------|
| `size` | Creams, perfumes, shampoos | 30ml, 50ml, 100ml, Large |
| `color` | Nail polish, eyeshadow | Red, Pink, Nude, Gold |
| `scent` | Perfumes, lotions | Rose, Lavender, Vanilla |
| `shade` | Foundation, lipstick | #01, #02, Fair, Medium |
| `finish` | Lipstick, nail polish | Matte, Glossy, Satin |
| `type` | Mascara, skincare | Volumizing, Hydrating |

---

### Table 2: `attribute_options`

**Purpose**: Values for each attribute (e.g., "Size has options: 50ml, 100ml")

```sql
attribute_options
├── id
├── product_attribute_id    ← FK to product_attributes
├── value                   ← '50ml', 'Red', 'Rose', etc.
├── display_order           ← For UI ordering
├── created_at
├── updated_at

INDEX (product_attribute_id)
```

---

### Table 3: `product_variants`

**Purpose**: Each purchasable combination with its own SKU and price

```sql
product_variants
├── id
├── product_id              ← FK to products
├── sku                     ← Unique identifier
├── name                    ← Auto-generated: "50ml - Red"
├── price                   ← Selling price
├── discount_price          ← Sale price (nullable)
├── is_active               ← Enable/disable
├── created_at
├── updated_at

UNIQUE (sku)
INDEX (product_id)
INDEX (is_active)
```

---

### Table 4: `variant_attribute_values`

**Purpose**: Links each variant to its attribute options

```sql
variant_attribute_values
├── id
├── product_variant_id      ← FK to product_variants
├── attribute_option_id     ← FK to attribute_options
├── created_at

UNIQUE (product_variant_id, attribute_option_id)
INDEX (product_variant_id)
INDEX (attribute_option_id)
```

---

### Example Data Flow

**Product: Luxury Perfume**

```
Step 1: Admin selects attributes
─────────────────────────────────
product_attributes:
├── { product_id: 1, attribute_name: 'size', display_order: 1 }
└── { product_id: 1, attribute_name: 'scent', display_order: 2 }

Step 2: Admin adds options
─────────────────────────────────
attribute_options:
├── { product_attribute_id: 1, value: '50ml' }    ← Size
├── { product_attribute_id: 1, value: '100ml' }   ← Size
├── { product_attribute_id: 2, value: 'Rose' }    ← Scent
└── { product_attribute_id: 2, value: 'Lavender' }← Scent

Step 3: System auto-generates 4 variants
─────────────────────────────────
product_variants:
├── { id: 1, sku: 'PERF-50-ROSE', name: '50ml - Rose', price: 2500 }
├── { id: 2, sku: 'PERF-50-LAV', name: '50ml - Lavender', price: 2500 }
├── { id: 3, sku: 'PERF-100-ROSE', name: '100ml - Rose', price: 4000 }
└── { id: 4, sku: 'PERF-100-LAV', name: '100ml - Lavender', price: 4000 }

Step 4: Link variants to options
─────────────────────────────────
variant_attribute_values:
├── { variant_id: 1, option_id: 1 }  ← 50ml
├── { variant_id: 1, option_id: 3 }  ← Rose
├── { variant_id: 2, option_id: 1 }  ← 50ml
├── { variant_id: 2, option_id: 4 }  ← Lavender
├── { variant_id: 3, option_id: 2 }  ← 100ml
├── { variant_id: 3, option_id: 3 }  ← Rose
├── { variant_id: 4, option_id: 2 }  ← 100ml
└── { variant_id: 4, option_id: 4 }  ← Lavender
```

---

## Part 2: Inventory System

### Table 5: `inventory`

**Purpose**: Current stock cache per variant (one row per variant)

```sql
inventory
├── id
├── product_variant_id      ← FK, UNIQUE
├── stock_quantity          ← Current available stock
├── reserved_quantity       ← Held for pending orders
├── low_stock_threshold     ← Alert threshold (default: 5)
├── created_at
├── updated_at

UNIQUE (product_variant_id)
INDEX (stock_quantity)
```

---

### Table 6: `inventory_movements`

**Purpose**: Log every stock IN/OUT transaction

```sql
inventory_movements
├── id
├── product_variant_id      ← FK to product_variants
├── type                    ← ENUM('in', 'reserved', 'sold', 'released', 'adjustment')
├── quantity                ← Always positive number
├── cost_per_unit           ← Purchase cost (for 'in' type only)
├── order_id                ← FK to orders (for reserved/sold/released)
├── supplier                ← Supplier name (for 'in' type)
├── notes
├── created_at
├── updated_at

INDEX (product_variant_id)
INDEX (type)
INDEX (order_id)
INDEX (created_at)
```

### Movement Types

| Type | Trigger | Inventory Effect |
|------|---------|------------------|
| `in` | Admin adds purchase | `stock_quantity += qty` |
| `reserved` | Order placed | `reserved_quantity += qty` |
| `sold` | Order completed | `stock -= qty`, `reserved -= qty` |
| `released` | Order cancelled | `reserved_quantity -= qty` |
| `adjustment` | Manual correction | `stock_quantity += or -= qty` |

---

## Part 3: Admin Workflow (Filament)

### Product Creation - Step 1: Basic Details

```
┌─────────────────────────────────────────────────────────┐
│ CREATE PRODUCT - Step 1: Basic Details                  │
├─────────────────────────────────────────────────────────┤
│                                                         │
│ Name:        [Luxury Perfume                    ]       │
│ Slug:        [luxury-perfume                    ] auto  │
│ Brand:       [▼ GlowSkin           ]                    │
│ Category:    [▼ Perfumes           ]                    │
│ Description: [Rich, long-lasting fragrance...   ]       │
│                                                         │
│ Main Image:  [📁 Upload]                                │
│                                                         │
│ ☑ Active    ☐ Featured                                  │
│                                                         │
│                      [Save & Continue to Variants →]    │
└─────────────────────────────────────────────────────────┘
```

### Product Creation - Step 2: Attributes & Variants

```
┌─────────────────────────────────────────────────────────┐
│ CREATE PRODUCT - Step 2: Attributes & Variants          │
├─────────────────────────────────────────────────────────┤
│                                                         │
│ SELECT ATTRIBUTES FOR THIS PRODUCT:                     │
│ ☑ Size    ☐ Color    ☑ Scent    ☐ Shade    ☐ Finish    │
│                                                         │
│ ─────────────────────────────────────────────────────── │
│                                                         │
│ SIZE OPTIONS:                                           │
│ [50ml ×] [100ml ×]                    [+ Add Option]    │
│                                                         │
│ SCENT OPTIONS:                                          │
│ [Rose ×] [Lavender ×]                 [+ Add Option]    │
│                                                         │
│                              [Generate Variants]        │
│ ─────────────────────────────────────────────────────── │
│                                                         │
│ GENERATED VARIANTS (4 combinations):                    │
│                                                         │
│ Bulk Price: [____]  [Apply to All]                      │
│                                                         │
│ ┌─────────────────────┬─────────────┬─────────┬───────┐ │
│ │ Variant             │ SKU         │ Price   │       │ │
│ ├─────────────────────┼─────────────┼─────────┼───────┤ │
│ │ 50ml - Rose         │ [PERF-50-R] │ [2500]  │ [🗑️]  │ │
│ │ 50ml - Lavender     │ [PERF-50-L] │ [2500]  │ [🗑️]  │ │
│ │ 100ml - Rose        │ [PERF-100-R]│ [4000]  │ [🗑️]  │ │
│ │ 100ml - Lavender    │ [PERF-100-L]│ [4000]  │ [🗑️]  │ │
│ └─────────────────────┴─────────────┴─────────┴───────┘ │
│                                                         │
│                                      [Save Product]     │
└─────────────────────────────────────────────────────────┘
```

### Product Edit - Add New Options

```
┌─────────────────────────────────────────────────────────┐
│ EDIT PRODUCT: Luxury Perfume                            │
├─────────────────────────────────────────────────────────┤
│                                                         │
│ CURRENT ATTRIBUTES:                                     │
│ ☑ Size    ☐ Color    ☑ Scent    ☐ Shade ← Can add new  │
│                                                         │
│ SIZE OPTIONS:                                           │
│ [50ml] [100ml] [+ 150ml]      ← Adding new option       │
│                                                         │
│ SCENT OPTIONS:                                          │
│ [Rose] [Lavender] [+ Vanilla] ← Adding new option       │
│                                                         │
│              [Generate New Combinations]                │
│ ─────────────────────────────────────────────────────── │
│                                                         │
│ EXISTING VARIANTS (4):                                  │
│ ├── 50ml - Rose         │ Rs. 2500 │ Stock: 15         │
│ ├── 50ml - Lavender     │ Rs. 2500 │ Stock: 8          │
│ ├── 100ml - Rose        │ Rs. 4000 │ Stock: 20         │
│ └── 100ml - Lavender    │ Rs. 4000 │ Stock: 12         │
│                                                         │
│ NEW VARIANTS TO CREATE (5):                             │
│ ☑ 50ml - Vanilla        │ [2500]   │ Will be added     │
│ ☑ 100ml - Vanilla       │ [4000]   │ Will be added     │
│ ☑ 150ml - Rose          │ [5500]   │ Will be added     │
│ ☑ 150ml - Lavender      │ [5500]   │ Will be added     │
│ ☑ 150ml - Vanilla       │ [5500]   │ Will be added     │
│                                                         │
│                                      [Save Changes]     │
└─────────────────────────────────────────────────────────┘
```

---

## Part 4: Frontend Display (Customer)

### Product Detail Page - Attribute Selection

```
┌─────────────────────────────────────────────────────────┐
│ LUXURY PERFUME                                          │
│ by GlowSkin                                             │
│                                                         │
│ ┌───────────────────┐                                   │
│ │                   │    Rs. 2,500                      │
│ │   [Product        │                                   │
│ │    Image]         │    Size:                          │
│ │                   │    [50ml] [100ml]                 │
│ └───────────────────┘     ^^^^^ selected                │
│                                                         │
│                          Scent:                         │
│                          [Rose] [Lavender]              │
│                           ^^^^ selected                 │
│                                                         │
│                          ✓ In Stock (15 available)      │
│                                                         │
│                          Quantity: [-] 1 [+]            │
│                                                         │
│                          [Add to Cart]                  │
└─────────────────────────────────────────────────────────┘
```

### Frontend Logic (JavaScript)

```javascript
// Product data from backend
const product = {
    attributes: [
        { name: 'size', options: ['50ml', '100ml'] },
        { name: 'scent', options: ['Rose', 'Lavender'] }
    ],
    variants: [
        { id: 1, options: { size: '50ml', scent: 'Rose' }, price: 2500, stock: 15 },
        { id: 2, options: { size: '50ml', scent: 'Lavender' }, price: 2500, stock: 8 },
        { id: 3, options: { size: '100ml', scent: 'Rose' }, price: 4000, stock: 20 },
        { id: 4, options: { size: '100ml', scent: 'Lavender' }, price: 4000, stock: 12 },
    ]
};

// When user selects options
function findMatchingVariant(selectedOptions) {
    // selectedOptions = { size: '50ml', scent: 'Rose' }
    return product.variants.find(variant => {
        return Object.entries(selectedOptions).every(
            ([attr, value]) => variant.options[attr] === value
        );
    });
}

// Update UI with variant details
function updateProductDisplay(variant) {
    if (variant) {
        document.getElementById('price').textContent = `Rs. ${variant.price}`;
        document.getElementById('stock').textContent = `${variant.stock} available`;
        document.getElementById('variant_id').value = variant.id;
        document.getElementById('add-to-cart').disabled = variant.stock <= 0;
    }
}
```

---

## Part 5: Stock Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    ADMIN ADDS PURCHASE                      │
│                                                             │
│  Movement: type='in', qty=20, cost=1500, supplier='ABC'    │
│  Inventory: stock_quantity += 20                            │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   CUSTOMER PLACES ORDER                     │
│                                                             │
│  Movement: type='reserved', qty=5, order_id=101            │
│  Inventory: reserved_quantity += 5                          │
└─────────────────────────────────────────────────────────────┘
                              │
              ┌───────────────┴───────────────┐
              ▼                               ▼
┌─────────────────────────────┐  ┌─────────────────────────────┐
│      ORDER COMPLETED        │  │      ORDER CANCELLED        │
│                             │  │                             │
│  Movement: type='sold'      │  │  Movement: type='released'  │
│  Inventory:                 │  │  Inventory:                 │
│   stock -= 5                │  │   reserved -= 5             │
│   reserved -= 5             │  │                             │
└─────────────────────────────┘  └─────────────────────────────┘
```

---

## Part 6: Report Queries

```sql
-- Total purchases this month
SELECT SUM(quantity) as total, SUM(quantity * cost_per_unit) as cost
FROM inventory_movements
WHERE type = 'in' AND created_at >= '2024-12-01';

-- Total sales this week
SELECT SUM(quantity) as sold
FROM inventory_movements
WHERE type = 'sold' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY);

-- Average cost per variant
SELECT SUM(quantity * cost_per_unit) / SUM(quantity) as avg_cost
FROM inventory_movements
WHERE product_variant_id = ? AND type = 'in';

-- Low stock alerts
SELECT pv.name, i.stock_quantity, i.low_stock_threshold
FROM inventory i
JOIN product_variants pv ON pv.id = i.product_variant_id
WHERE i.stock_quantity <= i.low_stock_threshold;

-- Get product with all attributes and variants (for frontend)
SELECT
    p.*,
    JSON_ARRAYAGG(
        JSON_OBJECT(
            'attribute', pa.attribute_name,
            'options', (
                SELECT JSON_ARRAYAGG(ao.value)
                FROM attribute_options ao
                WHERE ao.product_attribute_id = pa.id
            )
        )
    ) as attributes
FROM products p
JOIN product_attributes pa ON pa.product_id = p.id
WHERE p.id = ?
GROUP BY p.id;
```

---

## Part 7: Database Migration Tasks

### NEW Tables to Create

1. **`product_attributes`**
   ```sql
   CREATE TABLE product_attributes (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       product_id BIGINT UNSIGNED NOT NULL,
       attribute_name VARCHAR(50) NOT NULL,  -- 'size', 'color', 'scent', 'shade', 'finish', 'type'
       display_order INT DEFAULT 0,
       created_at TIMESTAMP NULL,
       updated_at TIMESTAMP NULL,

       FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
       UNIQUE KEY unique_product_attribute (product_id, attribute_name),
       INDEX idx_product_id (product_id)
   );
   ```

2. **`attribute_options`**
   ```sql
   CREATE TABLE attribute_options (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       product_attribute_id BIGINT UNSIGNED NOT NULL,
       value VARCHAR(100) NOT NULL,  -- '50ml', 'Red', 'Rose', etc.
       display_order INT DEFAULT 0,
       created_at TIMESTAMP NULL,
       updated_at TIMESTAMP NULL,

       FOREIGN KEY (product_attribute_id) REFERENCES product_attributes(id) ON DELETE CASCADE,
       INDEX idx_product_attribute_id (product_attribute_id)
   );
   ```

3. **`variant_attribute_values`**
   ```sql
   CREATE TABLE variant_attribute_values (
       id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
       product_variant_id BIGINT UNSIGNED NOT NULL,
       attribute_option_id BIGINT UNSIGNED NOT NULL,
       created_at TIMESTAMP NULL,

       FOREIGN KEY (product_variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
       FOREIGN KEY (attribute_option_id) REFERENCES attribute_options(id) ON DELETE CASCADE,
       UNIQUE KEY unique_variant_option (product_variant_id, attribute_option_id),
       INDEX idx_product_variant_id (product_variant_id),
       INDEX idx_attribute_option_id (attribute_option_id)
   );
   ```

### Tables to MODIFY

4. **`products`** - Remove columns
   ```sql
   ALTER TABLE products
   DROP COLUMN sku,           -- Now on variants only
   DROP COLUMN fragrance,     -- Now a variant attribute (scent)
   DROP COLUMN has_variants;  -- All products have variants now
   ```

5. **`product_variants`** - Remove flat attribute columns
   ```sql
   ALTER TABLE product_variants
   DROP COLUMN size,          -- Now in variant_attribute_values
   DROP COLUMN color,         -- Now in variant_attribute_values
   DROP COLUMN scent,         -- Now in variant_attribute_values
   DROP COLUMN cost_price;    -- Now tracked in inventory_movements
   ```

6. **`inventory`** - Simplify structure
   ```sql
   -- Rename columns
   ALTER TABLE inventory
   CHANGE COLUMN current_stock stock_quantity INT DEFAULT 0,
   CHANGE COLUMN reserved_stock reserved_quantity INT DEFAULT 0;

   -- Remove redundant column
   ALTER TABLE inventory
   DROP COLUMN product_id;

   -- Add unique constraint (if not exists)
   ALTER TABLE inventory
   ADD UNIQUE KEY unique_variant (product_variant_id);
   ```

7. **`inventory_movements`** - Restructure
   ```sql
   -- Add new columns
   ALTER TABLE inventory_movements
   ADD COLUMN product_variant_id BIGINT UNSIGNED AFTER id,
   ADD COLUMN supplier VARCHAR(255) NULL,
   ADD COLUMN order_id BIGINT UNSIGNED NULL;

   -- Remove old columns
   ALTER TABLE inventory_movements
   DROP COLUMN inventory_id,
   DROP COLUMN batch_number,
   DROP COLUMN reference_type;

   -- Rename columns
   ALTER TABLE inventory_movements
   CHANGE COLUMN movement_type type ENUM('in', 'reserved', 'sold', 'released', 'adjustment') NOT NULL,
   CHANGE COLUMN reference_id order_id_old BIGINT NULL;  -- Then drop after data migration

   -- Add indexes
   ALTER TABLE inventory_movements
   ADD FOREIGN KEY (product_variant_id) REFERENCES product_variants(id) ON DELETE CASCADE,
   ADD FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
   ADD INDEX idx_product_variant_id (product_variant_id),
   ADD INDEX idx_type (type),
   ADD INDEX idx_order_id (order_id);
   ```

### Summary of Changes

| Table | Action | Details |
|-------|--------|---------|
| `product_attributes` | CREATE | New - stores which attributes a product uses |
| `attribute_options` | CREATE | New - stores option values (50ml, Red, etc.) |
| `variant_attribute_values` | CREATE | New - links variants to their options |
| `products` | MODIFY | Remove: sku, fragrance, has_variants |
| `product_variants` | MODIFY | Remove: size, color, scent, cost_price |
| `inventory` | MODIFY | Rename columns, remove product_id |
| `inventory_movements` | MODIFY | Restructure for simpler IN/OUT tracking |

### Migration Order

1. Create new tables first (product_attributes, attribute_options, variant_attribute_values)
2. Migrate existing variant data to new attribute system
3. Modify products table (remove columns)
4. Modify product_variants table (remove columns)
5. Modify inventory table
6. Modify inventory_movements table

---

## Part 8: Filament Resources

### ProductResource

```php
// Two-step form with wizard or tabs
Wizard::make([
    Step::make('Basic Details')
        ->schema([
            TextInput::make('name')->required(),
            TextInput::make('slug')->unique(),
            Select::make('brand_id')->relationship('brand', 'name'),
            Select::make('category_id')->relationship('category', 'name'),
            RichEditor::make('description'),
            FileUpload::make('main_image'),
            Toggle::make('is_active'),
        ]),
    Step::make('Variants')
        ->schema([
            // Attribute selection checkboxes
            CheckboxList::make('selected_attributes')
                ->options([
                    'size' => 'Size',
                    'color' => 'Color',
                    'scent' => 'Scent',
                    'shade' => 'Shade',
                    'finish' => 'Finish',
                    'type' => 'Type',
                ]),

            // Dynamic options input per selected attribute
            // (handled via Livewire component)

            // Generated variants table
            // (handled via Livewire component)
        ]),
]);
```

### InventoryResource

```php
// Add Purchase Action
Action::make('addPurchase')
    ->form([
        Select::make('product_variant_id')
            ->options(ProductVariant::pluck('name', 'id')),
        TextInput::make('quantity')->numeric()->required(),
        TextInput::make('cost_per_unit')->numeric()->required(),
        TextInput::make('supplier'),
        Textarea::make('notes'),
    ])
    ->action(function (array $data) {
        // Create movement
        InventoryMovement::create([
            'product_variant_id' => $data['product_variant_id'],
            'type' => 'in',
            'quantity' => $data['quantity'],
            'cost_per_unit' => $data['cost_per_unit'],
            'supplier' => $data['supplier'],
            'notes' => $data['notes'],
        ]);

        // Update inventory cache
        Inventory::updateOrCreate(
            ['product_variant_id' => $data['product_variant_id']],
            []
        )->increment('stock_quantity', $data['quantity']);
    });
```

---

## Part 9: Business Rules

### Variants

1. Every product MUST have at least one variant
2. Every variant MUST have at least one attribute option
3. Price is ONLY on variants (not on products)
4. SKU must be unique across all variants

### Inventory

1. Stock tracked at VARIANT level
2. Reserved stock = pending orders
3. Available = stock_quantity - reserved_quantity
4. Low stock alert when stock <= threshold

### Orders

1. **COD Only** - No online payments
2. Order placed → reserve stock
3. Order completed → sold (reduce stock)
4. Order cancelled → release reserved

---

## Part 10: Filament Credentials

- **URL**: http://127.0.0.1:8000/admin
- **Email**: admin@chicchevron.lk
- **Password**: admin123

---

## Removed Features

| Feature | Status |
|---------|--------|
| PayHere Payment | Removed |
| Colors Table | Removed |
| Textures Table | Removed (text field) |
| Pages/FAQs/Banners Tables | Removed |
| FIFO Batch Tracking | Simplified |
| `has_variants` column | Removed |
| Flat variant columns | Replaced with attribute system |

---

## Part 11: Filament Admin Panel - Step by Step Implementation

### Phase 1: Models & Relationships (REQUIRED FIRST)

#### Step 1.1: Create New Models

```bash
php artisan make:model ProductAttribute
php artisan make:model AttributeOption
php artisan make:model VariantAttributeValue
```

#### Step 1.2: Update Existing Models

**Product.php** - Add relationships:
```php
// Remove: sku, fragrance, has_variants from $fillable

public function attributes(): HasMany
{
    return $this->hasMany(ProductAttribute::class)->orderBy('display_order');
}

public function variants(): HasMany
{
    return $this->hasMany(ProductVariant::class);
}
```

**ProductVariant.php** - Update:
```php
// Remove: size, color, scent, cost_price from $fillable

public function attributeValues(): HasMany
{
    return $this->hasMany(VariantAttributeValue::class);
}

public function attributeOptions(): BelongsToMany
{
    return $this->belongsToMany(AttributeOption::class, 'variant_attribute_values');
}

public function inventory(): HasOne
{
    return $this->hasOne(Inventory::class);
}

// Helper: Get attribute value by name
public function getAttributeValue(string $attributeName): ?string
{
    return $this->attributeOptions()
        ->whereHas('productAttribute', fn($q) => $q->where('attribute_name', $attributeName))
        ->first()?->value;
}
```

**ProductAttribute.php** - New:
```php
protected $fillable = ['product_id', 'attribute_name', 'display_order'];

public function product(): BelongsTo
{
    return $this->belongsTo(Product::class);
}

public function options(): HasMany
{
    return $this->hasMany(AttributeOption::class)->orderBy('display_order');
}
```

**AttributeOption.php** - New:
```php
protected $fillable = ['product_attribute_id', 'value', 'display_order'];

public function productAttribute(): BelongsTo
{
    return $this->belongsTo(ProductAttribute::class);
}

public function variants(): BelongsToMany
{
    return $this->belongsToMany(ProductVariant::class, 'variant_attribute_values');
}
```

**VariantAttributeValue.php** - New:
```php
protected $fillable = ['product_variant_id', 'attribute_option_id'];
public $timestamps = false; // Only has created_at

public function variant(): BelongsTo
{
    return $this->belongsTo(ProductVariant::class, 'product_variant_id');
}

public function option(): BelongsTo
{
    return $this->belongsTo(AttributeOption::class, 'attribute_option_id');
}
```

**Inventory.php** - Update:
```php
// Update $fillable: stock_quantity, reserved_quantity (renamed)
// Remove: product_id

public function variant(): BelongsTo
{
    return $this->belongsTo(ProductVariant::class, 'product_variant_id');
}

public function movements(): HasMany
{
    return $this->hasMany(InventoryMovement::class, 'product_variant_id', 'product_variant_id');
}

// Accessor: Available stock
public function getAvailableStockAttribute(): int
{
    return $this->stock_quantity - $this->reserved_quantity;
}
```

**InventoryMovement.php** - Update:
```php
protected $fillable = [
    'product_variant_id', 'type', 'quantity',
    'cost_per_unit', 'order_id', 'supplier', 'notes'
];

protected $casts = [
    'type' => 'string', // or create Enum class
    'quantity' => 'integer',
    'cost_per_unit' => 'decimal:2',
];

public function variant(): BelongsTo
{
    return $this->belongsTo(ProductVariant::class, 'product_variant_id');
}

public function order(): BelongsTo
{
    return $this->belongsTo(Order::class);
}
```

---

### Phase 2: Filament Resources - Basic CRUD

#### Step 2.1: Generate Resources

```bash
php artisan make:filament-resource Category --generate
php artisan make:filament-resource Brand --generate
php artisan make:filament-resource Product --generate
php artisan make:filament-resource Order --generate
```

#### Step 2.2: CategoryResource (Simple)

```php
// app/Filament/Resources/CategoryResource.php
public static function form(Form $form): Form
{
    return $form->schema([
        TextInput::make('name')->required()->maxLength(255),
        TextInput::make('slug')->required()->unique(ignoreRecord: true),
        Textarea::make('description'),
        FileUpload::make('image')->image()->directory('categories'),
        Toggle::make('is_active')->default(true),
    ]);
}

public static function table(Table $table): Table
{
    return $table->columns([
        ImageColumn::make('image'),
        TextColumn::make('name')->searchable()->sortable(),
        TextColumn::make('products_count')->counts('products'),
        IconColumn::make('is_active')->boolean(),
    ]);
}
```

#### Step 2.3: BrandResource (Simple)

```php
// Similar to CategoryResource
public static function form(Form $form): Form
{
    return $form->schema([
        TextInput::make('name')->required(),
        TextInput::make('slug')->required()->unique(ignoreRecord: true),
        Textarea::make('description'),
        FileUpload::make('logo')->image()->directory('brands'),
        Toggle::make('is_active')->default(true),
    ]);
}
```

---

### Phase 3: ProductResource with Variant System

#### Step 3.1: Basic Product Form (Create Page)

```php
// app/Filament/Resources/ProductResource/Pages/CreateProduct.php
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = ProductResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Basic Details')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Set $set) =>
                            $set('slug', Str::slug($state))),
                    TextInput::make('slug')
                        ->required()
                        ->unique(Product::class, 'slug', ignoreRecord: true),
                    Select::make('brand_id')
                        ->relationship('brand', 'name')
                        ->required()
                        ->searchable(),
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->required()
                        ->searchable(),
                    RichEditor::make('description')
                        ->columnSpanFull(),
                    FileUpload::make('main_image')
                        ->image()
                        ->directory('products')
                        ->required(),
                    Toggle::make('is_active')->default(true),
                ])
                ->columns(2),

            Step::make('Attributes & Variants')
                ->schema([
                    // See Step 3.2
                ]),
        ];
    }
}
```

#### Step 3.2: Attribute Selection & Variant Generation

```php
// In Step 2 of wizard
Step::make('Attributes & Variants')
    ->schema([
        Section::make('Select Attributes')
            ->schema([
                CheckboxList::make('selected_attributes')
                    ->options([
                        'size' => 'Size (30ml, 50ml, 100ml)',
                        'color' => 'Color (Red, Pink, Nude)',
                        'scent' => 'Scent (Rose, Lavender, Vanilla)',
                        'shade' => 'Shade (#01, #02, Fair, Medium)',
                        'finish' => 'Finish (Matte, Glossy, Satin)',
                        'type' => 'Type (Volumizing, Hydrating)',
                    ])
                    ->columns(3)
                    ->live(),
            ]),

        Section::make('Attribute Options')
            ->schema(fn (Get $get) => $this->getAttributeOptionsSchema($get))
            ->visible(fn (Get $get) => !empty($get('selected_attributes'))),

        Section::make('Generated Variants')
            ->schema([
                Placeholder::make('variants_preview')
                    ->content(fn (Get $get) => $this->getVariantsPreview($get)),

                TextInput::make('bulk_price')
                    ->numeric()
                    ->prefix('Rs.')
                    ->helperText('Apply this price to all variants'),

                Repeater::make('variants')
                    ->schema([
                        TextInput::make('name')->disabled(),
                        TextInput::make('sku')->required(),
                        TextInput::make('price')->numeric()->required()->prefix('Rs.'),
                        Toggle::make('is_active')->default(true),
                    ])
                    ->columns(4)
                    ->addable(false)
                    ->deletable(true),
            ])
            ->visible(fn (Get $get) => !empty($get('selected_attributes'))),
    ]),
```

#### Step 3.3: Variant Generation Service

```php
// app/Services/VariantGeneratorService.php
class VariantGeneratorService
{
    /**
     * Generate all possible variant combinations from attribute options
     */
    public function generateCombinations(array $attributeOptions): array
    {
        // $attributeOptions = [
        //     'size' => ['50ml', '100ml'],
        //     'scent' => ['Rose', 'Lavender'],
        // ]

        if (empty($attributeOptions)) {
            return [];
        }

        $attributes = array_keys($attributeOptions);
        $combinations = [[]];

        foreach ($attributeOptions as $attribute => $options) {
            $newCombinations = [];
            foreach ($combinations as $combination) {
                foreach ($options as $option) {
                    $newCombinations[] = array_merge($combination, [$attribute => $option]);
                }
            }
            $combinations = $newCombinations;
        }

        return $combinations;
    }

    /**
     * Generate variant name from options
     */
    public function generateVariantName(array $options): string
    {
        return implode(' - ', array_values($options));
    }

    /**
     * Generate SKU from product and options
     */
    public function generateSku(Product $product, array $options): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $product->name), 0, 4));
        $suffix = implode('-', array_map(fn($v) => strtoupper(substr($v, 0, 3)), $options));
        return "{$prefix}-{$suffix}-" . rand(100, 999);
    }
}
```

#### Step 3.4: Save Product with Variants

```php
// In CreateProduct.php
protected function afterCreate(): void
{
    $product = $this->record;
    $data = $this->form->getState();

    // Create attributes and options
    foreach ($data['selected_attributes'] as $attributeName) {
        $attribute = $product->attributes()->create([
            'attribute_name' => $attributeName,
            'display_order' => array_search($attributeName, $data['selected_attributes']),
        ]);

        // Create options for this attribute
        $optionKey = "{$attributeName}_options";
        if (!empty($data[$optionKey])) {
            foreach ($data[$optionKey] as $index => $optionValue) {
                $attribute->options()->create([
                    'value' => $optionValue,
                    'display_order' => $index,
                ]);
            }
        }
    }

    // Create variants
    $generator = new VariantGeneratorService();
    foreach ($data['variants'] as $variantData) {
        $variant = $product->variants()->create([
            'sku' => $variantData['sku'],
            'name' => $variantData['name'],
            'price' => $variantData['price'],
            'is_active' => $variantData['is_active'] ?? true,
        ]);

        // Link variant to attribute options
        foreach ($variantData['options'] as $attributeName => $optionValue) {
            $option = AttributeOption::whereHas('productAttribute', function ($q) use ($product, $attributeName) {
                $q->where('product_id', $product->id)
                  ->where('attribute_name', $attributeName);
            })->where('value', $optionValue)->first();

            if ($option) {
                VariantAttributeValue::create([
                    'product_variant_id' => $variant->id,
                    'attribute_option_id' => $option->id,
                ]);
            }
        }

        // Create inventory record with 0 stock
        Inventory::create([
            'product_variant_id' => $variant->id,
            'stock_quantity' => 0,
            'reserved_quantity' => 0,
            'low_stock_threshold' => 5,
        ]);
    }
}
```

---

### Phase 4: Inventory Management

#### Step 4.1: InventoryResource

```php
// app/Filament/Resources/InventoryResource.php
class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Inventory';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('variant.product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('variant.name')
                    ->label('Variant')
                    ->searchable(),
                TextColumn::make('variant.sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->sortable(),
                TextColumn::make('reserved_quantity')
                    ->label('Reserved')
                    ->sortable(),
                TextColumn::make('available_stock')
                    ->label('Available')
                    ->getStateUsing(fn ($record) => $record->stock_quantity - $record->reserved_quantity)
                    ->badge()
                    ->color(fn ($state) => $state <= 0 ? 'danger' : ($state <= 5 ? 'warning' : 'success')),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'low' => 'Low Stock',
                        'out' => 'Out of Stock',
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value']) {
                        'low' => $query->whereRaw('stock_quantity - reserved_quantity <= low_stock_threshold')
                                       ->whereRaw('stock_quantity - reserved_quantity > 0'),
                        'out' => $query->whereRaw('stock_quantity - reserved_quantity <= 0'),
                        default => $query,
                    }),
            ])
            ->actions([
                Action::make('addStock')
                    ->label('Add Stock')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->form([
                        TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        TextInput::make('cost_per_unit')
                            ->numeric()
                            ->required()
                            ->prefix('Rs.'),
                        TextInput::make('supplier')
                            ->maxLength(255),
                        Textarea::make('notes'),
                    ])
                    ->action(function (Inventory $record, array $data) {
                        // Create movement
                        InventoryMovement::create([
                            'product_variant_id' => $record->product_variant_id,
                            'type' => 'in',
                            'quantity' => $data['quantity'],
                            'cost_per_unit' => $data['cost_per_unit'],
                            'supplier' => $data['supplier'],
                            'notes' => $data['notes'],
                        ]);

                        // Update inventory
                        $record->increment('stock_quantity', $data['quantity']);

                        Notification::make()
                            ->success()
                            ->title('Stock added successfully')
                            ->send();
                    }),

                Action::make('adjust')
                    ->label('Adjust')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->form([
                        TextInput::make('new_quantity')
                            ->numeric()
                            ->required()
                            ->label('New Stock Quantity'),
                        Textarea::make('notes')
                            ->required()
                            ->label('Reason for adjustment'),
                    ])
                    ->action(function (Inventory $record, array $data) {
                        $difference = $data['new_quantity'] - $record->stock_quantity;

                        InventoryMovement::create([
                            'product_variant_id' => $record->product_variant_id,
                            'type' => 'adjustment',
                            'quantity' => abs($difference),
                            'notes' => ($difference >= 0 ? '+' : '-') . abs($difference) . ': ' . $data['notes'],
                        ]);

                        $record->update(['stock_quantity' => $data['new_quantity']]);
                    }),
            ]);
    }
}
```

#### Step 4.2: InventoryMovementResource (View Only)

```php
// app/Filament/Resources/InventoryMovementResource.php
class InventoryMovementResource extends Resource
{
    protected static ?string $model = InventoryMovement::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $navigationLabel = 'Stock History';

    public static function canCreate(): bool
    {
        return false; // Read-only
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('variant.product.name')
                    ->label('Product'),
                TextColumn::make('variant.name')
                    ->label('Variant'),
                BadgeColumn::make('type')
                    ->colors([
                        'success' => 'in',
                        'warning' => 'reserved',
                        'danger' => 'sold',
                        'info' => 'released',
                        'gray' => 'adjustment',
                    ]),
                TextColumn::make('quantity'),
                TextColumn::make('cost_per_unit')
                    ->money('LKR')
                    ->visible(fn ($record) => $record?->type === 'in'),
                TextColumn::make('supplier'),
                TextColumn::make('order.id')
                    ->label('Order #')
                    ->url(fn ($record) => $record->order_id
                        ? OrderResource::getUrl('view', ['record' => $record->order_id])
                        : null),
                TextColumn::make('notes')
                    ->limit(30),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'in' => 'Stock In',
                        'reserved' => 'Reserved',
                        'sold' => 'Sold',
                        'released' => 'Released',
                        'adjustment' => 'Adjustment',
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))),
            ]);
    }
}
```

---

### Phase 5: Order Management

#### Step 5.1: OrderResource

```php
// app/Filament/Resources/OrderResource.php
class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Order #')->sortable(),
                TextColumn::make('customer_name')->searchable(),
                TextColumn::make('customer_email')->searchable(),
                TextColumn::make('total_amount')->money('LKR'),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'processing')
                    ->requiresConfirmation()
                    ->action(fn ($record) => app(OrderService::class)->completeOrder($record)),

                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, ['pending', 'processing']))
                    ->requiresConfirmation()
                    ->action(fn ($record) => app(OrderService::class)->cancelOrder($record)),
            ]);
    }
}
```

#### Step 5.2: OrderService for Inventory Updates

```php
// app/Services/OrderService.php
class OrderService
{
    public function placeOrder(Order $order): void
    {
        foreach ($order->items as $item) {
            // Reserve stock
            InventoryMovement::create([
                'product_variant_id' => $item->product_variant_id,
                'type' => 'reserved',
                'quantity' => $item->quantity,
                'order_id' => $order->id,
            ]);

            Inventory::where('product_variant_id', $item->product_variant_id)
                ->increment('reserved_quantity', $item->quantity);
        }
    }

    public function completeOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                // Mark as sold
                InventoryMovement::create([
                    'product_variant_id' => $item->product_variant_id,
                    'type' => 'sold',
                    'quantity' => $item->quantity,
                    'order_id' => $order->id,
                ]);

                $inventory = Inventory::where('product_variant_id', $item->product_variant_id)->first();
                $inventory->decrement('stock_quantity', $item->quantity);
                $inventory->decrement('reserved_quantity', $item->quantity);
            }

            $order->update(['status' => 'completed']);
        });
    }

    public function cancelOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                // Release reserved stock
                InventoryMovement::create([
                    'product_variant_id' => $item->product_variant_id,
                    'type' => 'released',
                    'quantity' => $item->quantity,
                    'order_id' => $order->id,
                ]);

                Inventory::where('product_variant_id', $item->product_variant_id)
                    ->decrement('reserved_quantity', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);
        });
    }
}
```

---

### Phase 6: Dashboard Widgets

#### Step 6.1: Dashboard Stats

```php
// app/Filament/Widgets/StatsOverview.php
class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders', Order::count()),
            Stat::make('Pending Orders', Order::where('status', 'pending')->count())
                ->color('warning'),
            Stat::make('Low Stock Items', Inventory::whereRaw('stock_quantity - reserved_quantity <= low_stock_threshold')->count())
                ->color('danger'),
            Stat::make('Total Revenue', 'Rs. ' . number_format(Order::where('status', 'completed')->sum('total_amount')))
                ->color('success'),
        ];
    }
}
```

#### Step 6.2: Low Stock Alert Widget

```php
// app/Filament/Widgets/LowStockAlert.php
class LowStockAlert extends BaseWidget
{
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Low Stock Alerts')
            ->query(
                Inventory::query()
                    ->with('variant.product')
                    ->whereRaw('stock_quantity - reserved_quantity <= low_stock_threshold')
            )
            ->columns([
                TextColumn::make('variant.product.name')->label('Product'),
                TextColumn::make('variant.name')->label('Variant'),
                TextColumn::make('stock_quantity')->label('Current'),
                TextColumn::make('available')
                    ->getStateUsing(fn ($record) => $record->stock_quantity - $record->reserved_quantity)
                    ->badge()
                    ->color('danger'),
            ]);
    }
}
```

---

### Implementation Order Summary

| Phase | Task | Priority | Status |
|-------|------|----------|--------|
| 1 | Create/Update Models & Relationships | REQUIRED FIRST | ✅ DONE |
| 2 | Basic Resources (Category, Brand) | High | ✅ DONE |
| 3 | ProductResource with Tabs + Variants | High | ✅ DONE |
| 4 | InventoryResource + Stock Management | High | ✅ DONE |
| 5 | OrderResource + Status Flow + History | Medium | ✅ DONE |
| 6 | Dashboard Widgets | Low | ✅ DONE |
| 7 | ComplaintResource + Responses | Medium | ✅ DONE |

---

## Next Steps - Remaining Features

| # | Task | Status | Priority |
|---|------|--------|----------|
| 1 | ~~Create database migrations~~ | ✅ DONE | - |
| 2 | ~~Create/Update Eloquent Models~~ | ✅ DONE | - |
| 3 | ~~Create CategoryResource~~ | ✅ DONE | - |
| 4 | ~~Create BrandResource~~ | ✅ DONE | - |
| 5 | ~~Create ProductResource with Tabs + Variants~~ | ✅ DONE | - |
| 6 | ~~Create InventoryResource~~ | ✅ DONE | - |
| 7 | ~~Create InventoryMovementResource~~ | ✅ DONE | - |
| 8 | ~~Create OrderResource with Status Actions~~ | ✅ DONE | - |
| 9 | ~~Add Status History Timeline~~ | ✅ DONE | - |
| 10 | ~~Create Dashboard Widgets~~ | ✅ DONE | - |
| 11 | ~~Create ComplaintResource~~ | ✅ DONE | - |
| 12 | Create Reports Page (Sales/Inventory/Customers) | Pending | Low |
| 13 | Add Invoice PDF Download to Orders | Pending | Medium |
| 14 | Test Full Flow | Pending | High |

### What's Left to Implement

1. **Reports Section** (Low Priority)
   - Can use Filament Pages instead of Resources
   - Sales Report with date filters
   - Inventory Report with stock values
   - Customer Report with purchase patterns

2. **Invoice PDF Download** (Medium Priority)
   - Add download action to Order view/table
   - Use existing `invoice.blade.php` template with PDF generation

---

*Last Updated: December 2024*
*Maintained by: Claude AI Assistant*
