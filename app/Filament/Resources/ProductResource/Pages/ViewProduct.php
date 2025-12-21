<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\ProductAttribute;
use Filament\Actions;
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
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\HtmlString;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
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
                                            ->disabled(),

                                        TextInput::make('slug')
                                            ->disabled(),

                                        Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->disabled(),

                                        Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->disabled(),

                                        RichEditor::make('description')
                                            ->disabled()
                                            ->columnSpanFull(),

                                        TextInput::make('texture')
                                            ->disabled(),

                                        Textarea::make('how_to_use')
                                            ->label('How to Use')
                                            ->rows(3)
                                            ->disabled()
                                            ->columnSpanFull(),

                                        Textarea::make('suitable_for')
                                            ->label('Suitable For')
                                            ->rows(2)
                                            ->disabled()
                                            ->columnSpanFull(),

                                        Textarea::make('ingredients')
                                            ->rows(4)
                                            ->disabled()
                                            ->columnSpanFull(),

                                        Toggle::make('is_active')
                                            ->disabled(),
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
                                            ->disabled(),

                                        FileUpload::make('gallery_images')
                                            ->label('Gallery Images')
                                            ->image()
                                            ->multiple()
                                            ->disabled(),
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
                                            ->content(new HtmlString('<div class="text-center py-8 text-gray-500">No attributes defined for this product.</div>')),
                                    ];
                                }

                                $fields = [];
                                $attributes = $product->attributes()->with('options')->get();

                                foreach ($attributes as $attr) {
                                    $label = ProductAttribute::ATTRIBUTE_TYPES[$attr->attribute_name] ?? ucfirst($attr->attribute_name);

                                    // Build badges for each option value
                                    $badges = $attr->options->map(function ($opt) {
                                        return "<span class=\"inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-primary-100 text-primary-800 dark:bg-primary-800 dark:text-primary-100 mr-2 mb-2\">{$opt->value}</span>";
                                    })->implode('');

                                    $fields[] = Section::make($label . ' Options')
                                        ->schema([
                                            Placeholder::make("attr_{$attr->id}_display")
                                                ->label('')
                                                ->content(new HtmlString(
                                                    $badges ?: '<span class="text-gray-400">No options defined</span>'
                                                )),
                                        ])
                                        ->compact();
                                }

                                return $fields;
                            }),

                        Tabs\Tab::make('Variants')
                            ->icon('heroicon-o-squares-plus')
                            ->badge(fn () => $this->record ? $this->record->variants()->count() : 0)
                            ->schema([
                                Section::make('Product Variants')
                                    ->schema([
                                        Placeholder::make('no_variants_message')
                                            ->label('')
                                            ->content(new HtmlString('<div class="text-center py-4 text-gray-500">No variants created for this product.</div>'))
                                            ->visible(fn () => $this->record && $this->record->variants()->count() === 0),

                                        Repeater::make('variants')
                                            ->relationship('variants')
                                            ->schema([
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
                                                    ->disabled(),

                                                TextInput::make('sku')
                                                    ->label('SKU')
                                                    ->disabled(),

                                                TextInput::make('price')
                                                    ->prefix('Rs.')
                                                    ->label('Price')
                                                    ->disabled(),

                                                TextInput::make('discount_price')
                                                    ->prefix('Rs.')
                                                    ->label('Discount Price')
                                                    ->disabled(),

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
                                                    ->disabled()
                                                    ->inline(false),
                                            ])
                                            ->columns(7)
                                            ->reorderable(false)
                                            ->addable(false)
                                            ->deletable(false)
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
}
