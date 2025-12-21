<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Inventory;
use App\Models\ProductAttribute;
use App\Models\VariantAttributeValue;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Product')
                    ->tabs([
                        Tabs\Tab::make('Basic Details')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('URL slug will be auto-generated from product name'),

                                Select::make('brand_id')
                                    ->relationship('brand', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')->required(),
                                        TextInput::make('slug')->required(),
                                    ]),

                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')->required(),
                                        TextInput::make('slug')->required(),
                                    ]),

                                RichEditor::make('description')
                                    ->columnSpanFull(),

                                TextInput::make('texture')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Creamy, Lightweight, Gel'),

                                Textarea::make('how_to_use')
                                    ->rows(3)
                                    ->columnSpanFull()
                                    ->placeholder('Step-by-step instructions for using this product'),

                                Textarea::make('suitable_for')
                                    ->rows(2)
                                    ->columnSpanFull()
                                    ->placeholder('e.g., All skin types, Dry skin, Oily skin'),

                                Textarea::make('ingredients')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->placeholder('List ingredients separated by commas. e.g., Aqua, Glycerin, Niacinamide, Hyaluronic Acid...')
                                    ->helperText('Copy from product packaging. Will display nicely on frontend.'),

                                Toggle::make('is_active')
                                    ->default(true),
                            ])
                            ->columns(2),

                        Tabs\Tab::make('Images')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make('Product Images')
                                    ->schema([
                                        FileUpload::make('main_image')
                                            ->label('Main Image')
                                            ->image()
                                            ->directory('products')
                                            ->imageEditor()
                                            ->required()
                                            ->helperText('Primary product image shown in listings'),

                                        FileUpload::make('gallery_images')
                                            ->label('Gallery Images')
                                            ->image()
                                            ->multiple()
                                            ->reorderable()
                                            ->directory('products/gallery')
                                            ->imageEditor()
                                            ->maxFiles(6)
                                            ->helperText('Additional images (max 6). Drag to reorder.'),
                                    ]),
                            ]),

                        Tabs\Tab::make('Attributes & Variants')
                            ->icon('heroicon-o-squares-plus')
                            ->schema([
                                Section::make('Select Attributes')
                                    ->description('Choose which attributes this product has')
                                    ->schema([
                                        CheckboxList::make('selected_attributes')
                                            ->options(ProductAttribute::ATTRIBUTE_TYPES)
                                            ->columns(3)
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set) => $set('variants', [])),
                                    ]),

                                Section::make('Attribute Options')
                                    ->description('Add options for each attribute')
                                    ->schema(function (Get $get) {
                                        $selectedAttributes = $get('selected_attributes') ?? [];
                                        $fields = [];

                                        foreach ($selectedAttributes as $attribute) {
                                            $label = ProductAttribute::ATTRIBUTE_TYPES[$attribute] ?? ucfirst($attribute);

                                            $fields[] = Section::make($label . ' Options')
                                                ->description('Example: ' . $this->getAttributeExamples($attribute))
                                                ->schema([
                                                    Repeater::make("{$attribute}_options")
                                                        ->label('')
                                                        ->simple(
                                                            TextInput::make('value')
                                                                ->placeholder("Enter {$label} value")
                                                                ->required()
                                                                ->maxLength(100)
                                                        )
                                                        ->addActionLabel("Add {$label}")
                                                        ->defaultItems(0)
                                                        ->reorderable()
                                                        ->live()
                                                        ->afterStateUpdated(fn (Set $set) => $set('variants', []))
                                                        ->grid(4),
                                                ])
                                                ->compact();
                                        }

                                        return $fields;
                                    })
                                    ->visible(fn (Get $get) => !empty($get('selected_attributes'))),

                                Section::make('Default Pricing')
                                    ->description('Set default selling price for generated variants')
                                    ->schema([
                                        TextInput::make('bulk_price')
                                            ->numeric()
                                            ->prefix('Rs.')
                                            ->label('Default Selling Price')
                                            ->default(0)
                                            ->required()
                                            ->live(),
                                    ])
                                    ->visible(fn (Get $get) => !empty($get('selected_attributes'))),

                                Actions::make([
                                    Action::make('generateVariants')
                                        ->label('Generate Variants')
                                        ->icon('heroicon-o-sparkles')
                                        ->color('primary')
                                        ->size('lg')
                                        ->action(function (Get $get, Set $set) {
                                            $variants = $this->generateDefaultVariants($get);
                                            $set('variants', $variants);

                                            Notification::make()
                                                ->title(count($variants) . ' variant(s) generated!')
                                                ->success()
                                                ->send();
                                        })
                                        ->visible(fn (Get $get) => !empty($get('selected_attributes'))),
                                ])->columnSpanFull(),

                                Section::make('Generated Variants')
                                    ->description('Review and customize each variant')
                                    ->schema([
                                        Placeholder::make('no_variants')
                                            ->content('Click "Generate Variants" button above to create variant combinations.')
                                            ->visible(fn (Get $get) => empty($get('variants'))),

                                        Repeater::make('variants')
                                            ->schema([
                                                TextInput::make('name')
                                                    ->label('Variant Name')
                                                    ->required()
                                                    ->columnSpan(2),

                                                TextInput::make('sku')
                                                    ->label('SKU')
                                                    ->required()
                                                    ->columnSpan(2),

                                                TextInput::make('price')
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('Rs.')
                                                    ->label('Selling Price')
                                                    ->columnSpan(2),

                                                TextInput::make('discount_price')
                                                    ->numeric()
                                                    ->prefix('Rs.')
                                                    ->label('Discount Price')
                                                    ->columnSpan(2),

                                                Toggle::make('is_active')
                                                    ->label('Active')
                                                    ->default(true)
                                                    ->inline(false)
                                                    ->columnSpan(1),

                                                Hidden::make('options'),
                                            ])
                                            ->columns(9)
                                            ->addable(false)
                                            ->deletable(true)
                                            ->reorderable(true)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'Variant')
                                            ->visible(fn (Get $get) => !empty($get('variants'))),
                                    ])
                                    ->visible(fn (Get $get) => !empty($get('selected_attributes'))),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTabInQueryString(),
            ]);
    }

    protected function getAttributeExamples(string $attribute): string
    {
        return match ($attribute) {
            'size' => '30ml, 50ml, 100ml, 200ml',
            'color' => 'Red, Pink, Nude, Coral',
            'scent' => 'Rose, Lavender, Vanilla, Jasmine',
            'shade' => '#01, #02, Fair, Medium, Dark',
            'finish' => 'Matte, Glossy, Satin, Shimmer',
            'type' => 'Volumizing, Hydrating, Moisturizing',
            default => 'Option 1, Option 2, Option 3',
        };
    }

    protected function generateVariantCombinations(Get $get): array
    {
        $selectedAttributes = $get('selected_attributes') ?? [];
        $attributeOptions = [];

        foreach ($selectedAttributes as $attribute) {
            $options = $get("{$attribute}_options") ?? [];
            if (!empty($options)) {
                $values = collect($options)->map(function ($opt) {
                    return is_array($opt) ? ($opt['value'] ?? $opt) : $opt;
                })->filter()->values()->toArray();

                if (!empty($values)) {
                    $attributeOptions[$attribute] = $values;
                }
            }
        }

        if (empty($attributeOptions)) {
            return [];
        }

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

    protected function generateDefaultVariants(Get $get): array
    {
        $combinations = $this->generateVariantCombinations($get);
        $bulkPrice = $get('bulk_price') ?? 0;
        $productName = $get('name') ?? 'Product';
        $variants = [];

        foreach ($combinations as $index => $combination) {
            $variantName = implode(' - ', array_values($combination));
            $skuParts = array_map(
                fn($v) => strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $v), 0, 3)),
                array_values($combination)
            );
            $sku = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $productName), 0, 4))
                . '-' . implode('-', $skuParts)
                . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            $variants[] = [
                'name' => $variantName,
                'sku' => $sku,
                'price' => $bulkPrice,
                'discount_price' => null,
                'is_active' => true,
                'options' => $combination,
            ];
        }

        return $variants;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove non-model fields before creating
        unset($data['selected_attributes']);
        unset($data['bulk_price']);
        unset($data['variants']); // Remove variants - we'll create them in afterCreate

        // Remove attribute option fields
        foreach (array_keys(ProductAttribute::ATTRIBUTE_TYPES) as $attr) {
            unset($data["{$attr}_options"]);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $product = $this->record;
        $data = $this->form->getState();

        // Create attributes and options
        $selectedAttributes = $data['selected_attributes'] ?? [];
        $optionIds = [];

        foreach ($selectedAttributes as $index => $attributeName) {
            $attribute = $product->attributes()->create([
                'attribute_name' => $attributeName,
                'display_order' => $index,
            ]);

            $options = $data["{$attributeName}_options"] ?? [];
            foreach ($options as $optIndex => $optionData) {
                $optionValue = is_array($optionData) ? ($optionData['value'] ?? $optionData) : $optionData;

                if (empty($optionValue)) continue;

                $option = $attribute->options()->create([
                    'value' => $optionValue,
                    'display_order' => $optIndex,
                ]);
                $optionIds[$attributeName][$optionValue] = $option->id;
            }
        }

        // Create variants from form data
        $variantsData = $data['variants'] ?? [];

        foreach ($variantsData as $variantData) {
            // Create the variant
            $variant = $product->variants()->create([
                'name' => $variantData['name'],
                'sku' => $variantData['sku'],
                'price' => $variantData['price'] ?? 0,
                'discount_price' => $variantData['discount_price'] ?? null,
                'is_active' => $variantData['is_active'] ?? true,
            ]);

            // Link to attribute options
            if (isset($variantData['options']) && is_array($variantData['options'])) {
                foreach ($variantData['options'] as $attrName => $optionValue) {
                    if (isset($optionIds[$attrName][$optionValue])) {
                        VariantAttributeValue::create([
                            'product_variant_id' => $variant->id,
                            'attribute_option_id' => $optionIds[$attrName][$optionValue],
                        ]);
                    }
                }
            }

            // Create inventory record
            Inventory::create([
                'product_variant_id' => $variant->id,
                'stock_quantity' => 0,
                'reserved_quantity' => 0,
                'low_stock_threshold' => 5,
            ]);
        }
    }
}
