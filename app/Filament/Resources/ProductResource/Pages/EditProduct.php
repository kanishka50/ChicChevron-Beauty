<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\AttributeOption;
use App\Models\Inventory;
use App\Models\ProductAttribute;
use App\Models\VariantAttributeValue;
use Filament\Actions;
use Filament\Forms\Components\Actions as FormActions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
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
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load attribute options into the form data
        $product = $this->record;
        if ($product) {
            $attributes = $product->attributes()->with('options')->get();
            foreach ($attributes as $attr) {
                // For simple() repeater, just pass the value directly
                $data["attr_{$attr->id}_options"] = $attr->options->pluck('value')->toArray();
            }
        }

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Product')
                    ->tabs([
                        Tabs\Tab::make('Basic Info')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Product Details')
                                    ->schema([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),

                                        TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(ignoreRecord: true),

                                        Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload(),

                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload(),

                                        RichEditor::make('description')
                                            ->columnSpanFull(),

                                        TextInput::make('texture')
                                            ->maxLength(255),

                                        Textarea::make('how_to_use')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        Textarea::make('suitable_for')
                                            ->rows(2)
                                            ->columnSpanFull()
                                            ->placeholder('e.g., All skin types, Dry skin, Oily skin'),

                                        Textarea::make('ingredients')
                                            ->rows(4)
                                            ->columnSpanFull()
                                            ->placeholder('List ingredients separated by commas')
                                            ->helperText('Copy from product packaging'),

                                        Toggle::make('is_active')
                                            ->default(true),
                                    ])
                                    ->columns(2),
                            ]),

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
                                            ->helperText('Primary product image'),

                                        FileUpload::make('gallery_images')
                                            ->label('Gallery Images')
                                            ->image()
                                            ->multiple()
                                            ->reorderable()
                                            ->directory('products/gallery')
                                            ->imageEditor()
                                            ->maxFiles(6)
                                            ->helperText('Additional images (max 6)'),
                                    ]),
                            ]),

                        Tabs\Tab::make('Attributes & Options')
                            ->icon('heroicon-o-tag')
                            ->schema(function () {
                                $product = $this->record;
                                if (!$product || $product->attributes()->count() === 0) {
                                    return [
                                        Placeholder::make('no_attributes')
                                            ->label('')
                                            ->content(new HtmlString('<div class="text-center py-8 text-gray-500">No attributes defined for this product. Attributes are set during product creation.</div>')),
                                    ];
                                }

                                $fields = [];
                                $attributes = $product->attributes()->with('options')->get();

                                foreach ($attributes as $attr) {
                                    $label = ProductAttribute::ATTRIBUTE_TYPES[$attr->attribute_name] ?? ucfirst($attr->attribute_name);
                                    $attrId = $attr->id;

                                    $fields[] = Section::make($label . ' Options')
                                        ->description('Example: ' . $this->getAttributeExamples($attr->attribute_name))
                                        ->schema([
                                            Repeater::make("attr_{$attrId}_options")
                                                ->label('')
                                                ->simple(
                                                    TextInput::make('value')
                                                        ->placeholder("Enter {$label} value")
                                                        ->required()
                                                        ->maxLength(100)
                                                )
                                                ->addActionLabel("Add {$label}")
                                                ->reorderable()
                                                ->grid(4),
                                        ])
                                        ->compact();
                                }

                                $fields[] = Placeholder::make('attribute_note')
                                    ->label('')
                                    ->content(new HtmlString('<p class="text-sm text-gray-500 mt-4">After adding new options, go to the <strong>Variants</strong> tab to create new variant combinations.</p>'));

                                return $fields;
                            }),

                        Tabs\Tab::make('Variants')
                            ->icon('heroicon-o-squares-plus')
                            ->schema([
                                // Add New Variant Section
                                Section::make('Add New Variant')
                                    ->description('Create a new variant by selecting attribute options')
                                    ->schema(function () {
                                        $product = $this->record;
                                        $attributes = $product->attributes()->with('options')->get();

                                        if ($attributes->isEmpty()) {
                                            return [
                                                Placeholder::make('no_attributes')
                                                    ->label('')
                                                    ->content('No attributes defined for this product.')
                                            ];
                                        }

                                        $fields = [];

                                        // Create a select for each attribute
                                        foreach ($attributes as $attr) {
                                            $label = ProductAttribute::ATTRIBUTE_TYPES[$attr->attribute_name] ?? ucfirst($attr->attribute_name);
                                            $options = $attr->options->pluck('value', 'id')->toArray();

                                            $fields[] = Select::make("new_variant_{$attr->attribute_name}")
                                                ->label($label)
                                                ->options($options)
                                                ->placeholder("Select {$label}")
                                                ->native(false)
                                                ->dehydrated(false);
                                        }

                                        $fields[] = TextInput::make('new_variant_price')
                                            ->label('Price')
                                            ->numeric()
                                            ->prefix('Rs.')
                                            ->default(0)
                                            ->dehydrated(false);

                                        $fields[] = FormActions::make([
                                            Action::make('addNewVariant')
                                                ->label('Create Variant')
                                                ->icon('heroicon-o-plus-circle')
                                                ->color('success')
                                                ->size('lg')
                                                ->action(function (Get $get) {
                                                    $this->addNewVariant($get);
                                                }),
                                        ]);

                                        return $fields;
                                    })
                                    ->columns(4)
                                    ->collapsible(),

                                // Existing Variants Table
                                Section::make('All Variants (' . ($this->record ? $this->record->variants()->count() : 0) . ')')
                                    ->description('Edit prices, discount, SKU and status for all variants')
                                    ->schema([
                                        Placeholder::make('no_variants_message')
                                            ->label('')
                                            ->content(new HtmlString('<div class="text-center py-4 text-gray-500">No variants yet. Use the "Add New Variant" section above to create variants.</div>'))
                                            ->visible(fn () => $this->record && $this->record->variants()->count() === 0),

                                        Repeater::make('variants')
                                            ->relationship('variants')
                                            ->schema([
                                                // Variant options (read-only badges)
                                                Placeholder::make('variant_options')
                                                    ->label('Options')
                                                    ->content(function ($record) {
                                                        if (!$record) {
                                                            return '-';
                                                        }

                                                        $options = $record->attributeOptions()->with('productAttribute')->get();

                                                        if ($options->isEmpty()) {
                                                            return new HtmlString('<span class="text-gray-400">' . ($record->name ?? 'No options') . '</span>');
                                                        }

                                                        $badges = $options->map(function ($opt) {
                                                            $attrName = ProductAttribute::ATTRIBUTE_TYPES[$opt->productAttribute->attribute_name ?? ''] ?? '';
                                                            return "<span class=\"inline-flex items-center px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 mr-1 mb-1\">{$attrName}: {$opt->value}</span>";
                                                        })->implode('');

                                                        return new HtmlString($badges);
                                                    }),

                                                TextInput::make('name')
                                                    ->label('Name')
                                                    ->required()
                                                    ->maxLength(255),

                                                TextInput::make('sku')
                                                    ->label('SKU')
                                                    ->required()
                                                    ->maxLength(50)
                                                    ->unique(ignoreRecord: true),

                                                TextInput::make('price')
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('Rs.')
                                                    ->label('Price'),

                                                TextInput::make('discount_price')
                                                    ->numeric()
                                                    ->prefix('Rs.')
                                                    ->label('Discount Price'),

                                                Placeholder::make('current_stock')
                                                    ->label('Stock')
                                                    ->content(function ($record) {
                                                        if (!$record || !$record->inventory) {
                                                            return new HtmlString('<span class="text-gray-400">0</span>');
                                                        }
                                                        $available = $record->inventory->stock_quantity - $record->inventory->reserved_quantity;
                                                        $colorClass = $available <= 0 ? 'text-red-600' : ($available <= 10 ? 'text-yellow-600' : 'text-green-600');
                                                        return new HtmlString("<span class=\"font-bold {$colorClass}\">{$available}</span>");
                                                    }),

                                                Toggle::make('is_active')
                                                    ->label('Active')
                                                    ->default(true)
                                                    ->inline(false),
                                            ])
                                            ->columns(7)
                                            ->reorderable(false)
                                            ->addable(false)
                                            ->deletable(true)
                                            ->deleteAction(
                                                fn (Action $action) => $action->requiresConfirmation()
                                            )
                                            ->itemLabel(fn (array $state): ?string =>
                                                ($state['name'] ?? 'Variant') . ' (SKU: ' . ($state['sku'] ?? '-') . ')'
                                            )
                                            ->visible(fn () => $this->record && $this->record->variants()->count() > 0),
                                    ]),
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

    protected function addNewVariant(Get $get): void
    {
        $product = $this->record;
        // Refresh attributes to get latest options (including newly added ones)
        $product->load('attributes.options');
        $attributes = $product->attributes;

        if ($attributes->isEmpty()) {
            Notification::make()
                ->title('No attributes')
                ->body('This product has no attributes defined.')
                ->warning()
                ->send();
            return;
        }

        // Collect selected option IDs
        $selectedOptions = [];
        $variantNameParts = [];

        foreach ($attributes as $attr) {
            $fieldName = "new_variant_{$attr->attribute_name}";
            $optionId = $get($fieldName);

            if (!$optionId) {
                Notification::make()
                    ->title('Missing selection')
                    ->body("Please select a " . (ProductAttribute::ATTRIBUTE_TYPES[$attr->attribute_name] ?? $attr->attribute_name) . " option.")
                    ->warning()
                    ->send();
                return;
            }

            $option = AttributeOption::find($optionId);
            if ($option) {
                $selectedOptions[$optionId] = $option;
                $variantNameParts[] = $option->value;
            }
        }

        // Check if this combination already exists
        $existingVariant = null;
        foreach ($product->variants as $variant) {
            $variantOptionIds = $variant->attributeOptions->pluck('id')->sort()->values()->toArray();
            $selectedOptionIds = collect(array_keys($selectedOptions))->sort()->values()->toArray();

            if ($variantOptionIds == $selectedOptionIds) {
                $existingVariant = $variant;
                break;
            }
        }

        if ($existingVariant) {
            Notification::make()
                ->title('Variant already exists')
                ->body("A variant with these options already exists: {$existingVariant->name}")
                ->warning()
                ->send();
            return;
        }

        // Generate variant name and SKU
        $variantName = implode(' - ', $variantNameParts);
        $skuParts = collect($variantNameParts)->map(function ($v) {
            return strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $v), 0, 3));
        })->toArray();
        $sku = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $product->name), 0, 4))
            . '-' . implode('-', $skuParts)
            . '-' . Str::random(4);

        $price = $get('new_variant_price') ?? 0;

        // Create the variant
        $variant = $product->variants()->create([
            'name' => $variantName,
            'sku' => $sku,
            'price' => $price,
            'is_active' => true,
        ]);

        // Link to attribute options
        foreach ($selectedOptions as $optionId => $option) {
            VariantAttributeValue::create([
                'product_variant_id' => $variant->id,
                'attribute_option_id' => $optionId,
            ]);
        }

        // Create inventory record
        Inventory::create([
            'product_variant_id' => $variant->id,
            'stock_quantity' => 0,
            'reserved_quantity' => 0,
            'low_stock_threshold' => 5,
        ]);

        Notification::make()
            ->title('Variant created!')
            ->body("New variant '{$variantName}' has been added successfully.")
            ->success()
            ->send();

        // Refresh the form to show the new variant
        $this->fillForm();
    }

    protected function afterSave(): void
    {
        $product = $this->record;
        $data = $this->form->getState();

        // Save attribute options
        $attributes = $product->attributes()->with('options')->get();
        foreach ($attributes as $attr) {
            $fieldName = "attr_{$attr->id}_options";
            $optionsData = $data[$fieldName] ?? [];

            // Get submitted values (simple() repeater returns flat values)
            $submittedValues = [];
            foreach ($optionsData as $optionData) {
                $value = is_array($optionData) ? ($optionData['value'] ?? $optionData) : $optionData;
                if (!empty($value)) {
                    $submittedValues[] = $value;
                }
            }

            // Get existing options
            $existingOptions = $attr->options;

            // Update or create options based on submitted values
            foreach ($submittedValues as $index => $value) {
                // Try to find existing option with this value
                $existingOption = $existingOptions->firstWhere('value', $value);

                if ($existingOption) {
                    // Update display order if needed
                    if ($existingOption->display_order !== $index) {
                        $existingOption->update(['display_order' => $index]);
                    }
                } else {
                    // Create new option
                    $attr->options()->create([
                        'value' => $value,
                        'display_order' => $index,
                    ]);
                }
            }

            // Delete options that are no longer in the submitted list (if not in use by variants)
            foreach ($existingOptions as $existingOption) {
                if (!in_array($existingOption->value, $submittedValues)) {
                    // Only delete if not used by any variants
                    if ($existingOption->variants()->count() === 0) {
                        $existingOption->delete();
                    }
                }
            }
        }

        // Ensure all variants have inventory records
        foreach ($product->variants as $variant) {
            if (!$variant->inventory) {
                Inventory::create([
                    'product_variant_id' => $variant->id,
                    'stock_quantity' => 0,
                    'reserved_quantity' => 0,
                    'low_stock_threshold' => 5,
                ]);
            }
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove non-model fields
        $product = $this->record;

        if ($product && $product->attributes) {
            foreach ($product->attributes as $attr) {
                unset($data["new_variant_{$attr->attribute_name}"]);
                unset($data["attr_{$attr->id}_options"]);
            }
        }
        unset($data['new_variant_price']);

        return $data;
    }
}
