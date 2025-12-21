# Product Variant System Redesign

## Overview

This document describes the redesigned product variant system for NADZ Beauty e-commerce platform. The new system follows industry standards used by Shopify, Sephora, and Amazon for cosmetics e-commerce.

## Key Principles

1. **Every product has at least one variant** - No "products without variants"
2. **Every variant has at least one attribute** - No "variants without attributes"
3. **Attributes are system-defined** - Pre-defined list (Size, Shade, Color, Scent, Finish, Type)
4. **Price and stock are ONLY stored in variants table** - Products table has no price/stock
5. **Unified product creation flow** - Single page for product + attributes + variants

---

## Database Schema

### Tables Overview

```
┌─────────────────────┐     ┌─────────────────────────┐
│  system_attributes  │     │       products          │
│  ─────────────────  │     │  ───────────────────    │
│  id                 │     │  id                     │
│  name (unique)      │     │  name, slug, sku        │
│  display_name       │     │  brand_id, category_id  │
│  display_order      │     │  description            │
│  is_active          │     │  is_active, is_featured │
└─────────────────────┘     │  (NO price, NO stock)   │
          │                 └─────────────────────────┘
          │                            │
          ▼                            ▼
┌─────────────────────────────────────────────────────┐
│           product_attributes                         │
│  ─────────────────────────────────────────────────  │
│  id                                                 │
│  product_id → products.id                           │
│  system_attribute_id → system_attributes.id         │
│  display_order                                      │
└─────────────────────────────────────────────────────┘
          │
          ▼
┌─────────────────────────────────────────────────────┐
│           attribute_options                          │
│  ─────────────────────────────────────────────────  │
│  id                                                 │
│  product_attribute_id → product_attributes.id       │
│  value (e.g., "30ml", "Red", "#05")                 │
│  display_order                                      │
└─────────────────────────────────────────────────────┘
          │
          ▼
┌─────────────────────────────────────────────────────┐
│           product_variants                           │
│  ─────────────────────────────────────────────────  │
│  id                                                 │
│  product_id → products.id                           │
│  sku (unique)                                       │
│  price (required)                                   │
│  compare_at_price                                   │
│  cost_price                                         │
│  stock_quantity                                     │
│  low_stock_threshold                                │
│  weight, barcode                                    │
│  is_active                                          │
└─────────────────────────────────────────────────────┘
          │
          ▼
┌─────────────────────────────────────────────────────┐
│        variant_attribute_values                      │
│  ─────────────────────────────────────────────────  │
│  id                                                 │
│  variant_id → product_variants.id                   │
│  attribute_option_id → attribute_options.id         │
│  (Links each variant to its attribute values)       │
└─────────────────────────────────────────────────────┘
```

### System Attributes (Pre-defined)

| ID | Name   | Display Name | Use Case                              |
|----|--------|--------------|---------------------------------------|
| 1  | size   | Size         | Creams, shampoos, perfumes (30ml, 50ml) |
| 2  | shade  | Shade        | Foundations, lipsticks (#01, #02)     |
| 3  | color  | Color        | Nail polish, eyeshadow (Red, Pink)    |
| 4  | scent  | Scent        | Perfumes, lotions (Vanilla, Lavender) |
| 5  | finish | Finish       | Lipsticks (Matte, Glossy, Satin)      |
| 6  | type   | Type         | Mascara (Volumizing, Lengthening)     |

### Removed/Changed

| Old                        | New                                  |
|----------------------------|--------------------------------------|
| `products.has_variants`    | REMOVED - all products have variants |
| `products.price`           | NEVER EXISTED - always use variants  |
| `product_attributes.attribute_name` | Changed to `system_attribute_id` reference |

---

## Data Flow Examples

### Example 1: Simple Product (Single Attribute)

**Product:** Hydrating Face Cream

```
Product:
├── name: "Hydrating Face Cream"
├── brand: "GlowSkin"
├── category: "Skincare > Moisturizers"
│
├── Attributes Selected: [Size]
│   └── Size Values: ["30ml", "50ml", "100ml"]
│
└── Variants (auto-generated):
    ├── Variant 1: Size=30ml, SKU=HFC-30ML, Price=1500, Stock=50
    ├── Variant 2: Size=50ml, SKU=HFC-50ML, Price=2500, Stock=30
    └── Variant 3: Size=100ml, SKU=HFC-100ML, Price=4000, Stock=20
```

### Example 2: Complex Product (Multiple Attributes)

**Product:** Luxury Matte Lipstick

```
Product:
├── name: "Luxury Matte Lipstick"
├── brand: "LuxeLips"
├── category: "Makeup > Lipsticks"
│
├── Attributes Selected: [Shade, Finish]
│   ├── Shade Values: ["Red Velvet", "Pink Rose", "Nude"]
│   └── Finish Values: ["Matte", "Glossy"]
│
└── Variants (auto-generated - 6 combinations):
    ├── Red Velvet + Matte    → SKU=LML-RV-M, Price=2500
    ├── Red Velvet + Glossy   → SKU=LML-RV-G, Price=2500
    ├── Pink Rose + Matte     → SKU=LML-PR-M, Price=2500
    ├── Pink Rose + Glossy    → SKU=LML-PR-G, Price=2500
    ├── Nude + Matte          → SKU=LML-NU-M, Price=2500
    └── Nude + Glossy         → SKU=LML-NU-G, Price=2500

Admin can DELETE unwanted combinations (e.g., Nude + Glossy)
```

### Example 3: Minimal Product (One Attribute, One Value)

**Product:** Limited Edition Gift Set

```
Product:
├── name: "Holiday Gift Set 2024"
├── brand: "NADZ Beauty"
│
├── Attributes Selected: [Type]
│   └── Type Values: ["Standard"]  (just 1 value)
│
└── Variants:
    └── Variant 1: Type=Standard, SKU=HGS-2024, Price=5000, Stock=100
```

---

## Admin UI Flow

### Create Product Page (Unified)

```
┌──────────────────────────────────────────────────────────────────┐
│  CREATE PRODUCT                                    [Save] [Cancel]│
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  BASIC INFORMATION                                               │
│  ────────────────────────────────────────────────────────────    │
│  Name:        [_______________________________]                  │
│  Brand:       [▼ Select Brand    ]                               │
│  Category:    [▼ Select Category ]                               │
│  Description: [_______________________________]                  │
│               [_______________________________]                  │
│                                                                  │
│  PRODUCT ATTRIBUTES                                              │
│  ────────────────────────────────────────────────────────────    │
│  Select which attributes apply to this product:                  │
│                                                                  │
│  ☑ Size    ☐ Shade    ☐ Color    ☐ Scent    ☐ Finish   ☐ Type  │
│                                                                  │
│  SIZE VALUES:                                                    │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │ [30ml ×] [50ml ×] [100ml ×]           [+ Add Value]      │    │
│  └──────────────────────────────────────────────────────────┘    │
│                                                                  │
│  VARIANTS                                                        │
│  ────────────────────────────────────────────────────────────    │
│  Bulk Settings: Price [____] Stock [____] [Apply to All]         │
│                                                                  │
│  ┌─────────┬────────────┬─────────┬───────┬──────────────────┐   │
│  │ Variant │ SKU        │ Price   │ Stock │ Actions          │   │
│  ├─────────┼────────────┼─────────┼───────┼──────────────────┤   │
│  │ 30ml    │ PROD-30ML  │ [1500]  │ [50]  │ [Edit] [Delete]  │   │
│  │ 50ml    │ PROD-50ML  │ [2500]  │ [30]  │ [Edit] [Delete]  │   │
│  │ 100ml   │ PROD-100ML │ [4000]  │ [20]  │ [Edit] [Delete]  │   │
│  └─────────┴────────────┴─────────┴───────┴──────────────────┘   │
│                                                                  │
│  [+ Add Missing Combination]  (for restoring deleted variants)   │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

### Edit Variant Modal

```
┌─────────────────────────────────────────────┐
│  EDIT VARIANT: 30ml                    [×]  │
├─────────────────────────────────────────────┤
│                                             │
│  SKU:              [PROD-30ML        ]      │
│  Price:            [1500             ] LKR  │
│  Compare at Price: [1800             ] LKR  │
│  Cost Price:       [800              ] LKR  │
│                                             │
│  Stock Quantity:   [50               ]      │
│  Low Stock Alert:  [10               ]      │
│                                             │
│  Weight:           [0.3              ] kg   │
│  Barcode:          [                 ]      │
│                                             │
│  ☑ Active                                   │
│                                             │
│              [Cancel]  [Save Changes]       │
└─────────────────────────────────────────────┘
```

---

## Frontend (Customer) Display

### Product Detail Page

```
┌──────────────────────────────────────────────────────────────────┐
│  HYDRATING FACE CREAM                                            │
│  by GlowSkin                                                     │
│                                                                  │
│  ┌─────────────────┐                                             │
│  │                 │    LKR 2,500                                │
│  │   [Product      │    ──────────                               │
│  │    Image]       │                                             │
│  │                 │    Size:                                    │
│  └─────────────────┘    [30ml] [50ml] [100ml]  ← Selected        │
│                               ^^^^^^                             │
│                                                                  │
│                         In Stock (30 available)                  │
│                                                                  │
│                         Quantity: [-] 1 [+]                      │
│                                                                  │
│                         [Add to Cart]                            │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

### Variant Selection Logic

```typescript
// When customer selects attributes, find matching variant
const selectedAttributes = { size: "50ml" };

const matchingVariant = product.variants.find(variant => {
  return Object.entries(selectedAttributes).every(
    ([attrName, attrValue]) => variant.attributes[attrName] === attrValue
  );
});

if (matchingVariant) {
  displayPrice(matchingVariant.price);
  displayStock(matchingVariant.stock_quantity);
}
```

---

## API Endpoints

### Admin Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/admin/system-attributes` | Get all system attributes |
| GET | `/api/admin/products` | List all products |
| POST | `/api/admin/products` | Create product with attributes & variants |
| PUT | `/api/admin/products/:id` | Update product |
| DELETE | `/api/admin/products/:id` | Delete product |
| PUT | `/api/admin/variants/:id` | Update single variant |
| DELETE | `/api/admin/variants/:id` | Delete single variant |
| POST | `/api/admin/products/:id/variants/generate` | Regenerate variants |

### Public Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/products` | List products (with min/max price from variants) |
| GET | `/api/products/:slug` | Get product with all active variants |

### Request/Response Examples

#### Create Product Request

```json
POST /api/admin/products
{
  "name": "Hydrating Face Cream",
  "brand_id": 1,
  "category_id": 5,
  "description": "Rich moisturizing cream...",
  "attributes": [
    {
      "system_attribute_id": 1,  // Size
      "values": ["30ml", "50ml", "100ml"]
    }
  ],
  "variants": [
    {
      "attribute_values": { "size": "30ml" },
      "sku": "HFC-30ML",
      "price": 1500,
      "stock_quantity": 50
    },
    {
      "attribute_values": { "size": "50ml" },
      "sku": "HFC-50ML",
      "price": 2500,
      "stock_quantity": 30
    },
    {
      "attribute_values": { "size": "100ml" },
      "sku": "HFC-100ML",
      "price": 4000,
      "stock_quantity": 20
    }
  ]
}
```

#### Get Product Response

```json
GET /api/products/hydrating-face-cream
{
  "success": true,
  "data": {
    "product": {
      "id": 1,
      "name": "Hydrating Face Cream",
      "slug": "hydrating-face-cream",
      "brand_name": "GlowSkin",
      "category_name": "Moisturizers",
      "description": "Rich moisturizing cream...",
      "min_price": 1500,
      "max_price": 4000,
      "total_stock": 100,
      "attributes": [
        {
          "name": "size",
          "display_name": "Size",
          "values": ["30ml", "50ml", "100ml"]
        }
      ],
      "variants": [
        {
          "id": 1,
          "sku": "HFC-30ML",
          "price": 1500,
          "stock_quantity": 50,
          "is_active": true,
          "attributes": { "size": "30ml" }
        },
        {
          "id": 2,
          "sku": "HFC-50ML",
          "price": 2500,
          "stock_quantity": 30,
          "is_active": true,
          "attributes": { "size": "50ml" }
        },
        {
          "id": 3,
          "sku": "HFC-100ML",
          "price": 4000,
          "stock_quantity": 20,
          "is_active": true,
          "attributes": { "size": "100ml" }
        }
      ],
      "images": [...]
    }
  }
}
```

---

## Migration Guide

### From Old System to New System

1. **Create `system_attributes` table** with pre-defined attributes
2. **Modify `product_attributes` table** to reference `system_attributes`
3. **Remove `has_variants` column** from `products` table
4. **Migrate existing data**:
   - Map existing `attribute_name` values to `system_attribute_id`
   - Ensure all products have at least 1 variant
   - Link all variants to attribute values

### Data Migration Script

See: `nadz-backend/migrations/variant_system_migration.sql`

---

## Validation Rules

### Product Creation

- Name: Required, 1-255 characters
- Brand: Required
- Category: Required
- At least 1 attribute must be selected
- Each selected attribute must have at least 1 value
- At least 1 variant must exist after creation

### Variant

- SKU: Required, unique across all variants
- Price: Required, must be > 0
- Stock: Required, must be >= 0
- Must have values for ALL selected product attributes

### Attribute Values

- Cannot delete an attribute value if variants use it
- When adding new value, prompt to generate new variant combinations

---

## Files to Update

### Backend

| File | Changes |
|------|---------|
| `nadz-backend/src/models/productModel.ts` | Remove `has_variants` handling |
| `nadz-backend/src/models/productVariantModel.ts` | Update queries for new schema |
| `nadz-backend/src/models/productAttributeModel.ts` | Reference `system_attributes` |
| `nadz-backend/src/controllers/admin/productController.ts` | Unified create/update |
| `nadz-backend/src/controllers/public/productController.ts` | Update response format |
| `nadz-backend/src/routes/admin/products.ts` | New endpoint structure |

### Frontend (Admin)

| File | Changes |
|------|---------|
| `src/app/admin/products/page.tsx` | Unified product creation form |
| `src/app/admin/products/[id]/variants/page.tsx` | REMOVE (merge into product page) |
| `src/app/admin/products/[id]/attributes/page.tsx` | REMOVE (merge into product page) |
| `src/app/admin/products/[id]/variants-builder/page.tsx` | REMOVE (merge into product page) |
| `src/components/admin/ProductForm.tsx` | NEW - unified form component |
| `src/components/admin/VariantTable.tsx` | NEW - variant management component |

### Frontend (Customer)

| File | Changes |
|------|---------|
| `src/app/products/[slug]/page.tsx` | Update variant selection logic |
| `src/lib/api.ts` | Update types for new response format |

---

## Testing Checklist

### Admin

- [ ] Can create product with 1 attribute, 1 value (minimum)
- [ ] Can create product with multiple attributes (generates combinations)
- [ ] Can delete individual variants
- [ ] Can restore deleted variants
- [ ] Can add new attribute value (prompts for new variants)
- [ ] Can bulk edit variant prices/stock
- [ ] Can edit individual variant details
- [ ] Validation prevents saving invalid data

### Customer

- [ ] Product page shows attribute selection buttons
- [ ] Selecting attributes updates price display
- [ ] Selecting attributes updates stock display
- [ ] Can add to cart with selected variant
- [ ] Out of stock variants show correctly

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 2.0.0 | 2024-12-XX | Complete variant system redesign |
| 1.0.0 | Previous | Original system with `has_variants` flag |
