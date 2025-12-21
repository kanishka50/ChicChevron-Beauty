<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Product')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Basic Info')
                            ->schema([
                                Forms\Components\Section::make('Product Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) =>
                                                $set('slug', Str::slug($state))),

                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(Product::class, 'slug', ignoreRecord: true),

                                        Forms\Components\Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload(),

                                        Forms\Components\Select::make('category_id')
                                            ->relationship('category', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload(),

                                        Forms\Components\RichEditor::make('description')
                                            ->columnSpanFull(),

                                        Forms\Components\TextInput::make('texture')
                                            ->maxLength(255),

                                        Forms\Components\Textarea::make('how_to_use')
                                            ->rows(3)
                                            ->columnSpanFull(),

                                        Forms\Components\Textarea::make('suitable_for')
                                            ->rows(2)
                                            ->columnSpanFull()
                                            ->placeholder('e.g., All skin types, Dry skin, Oily skin'),

                                        Forms\Components\Textarea::make('ingredients')
                                            ->rows(4)
                                            ->columnSpanFull()
                                            ->placeholder('List ingredients separated by commas')
                                            ->helperText('Copy from product packaging'),

                                        Forms\Components\Toggle::make('is_active')
                                            ->default(true),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Images')
                            ->schema([
                                Forms\Components\Section::make('Product Images')
                                    ->schema([
                                        Forms\Components\FileUpload::make('main_image')
                                            ->label('Main Image')
                                            ->image()
                                            ->directory('products')
                                            ->imageEditor()
                                            ->required()
                                            ->helperText('Primary product image'),

                                        Forms\Components\FileUpload::make('gallery_images')
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

                        Forms\Components\Tabs\Tab::make('Variants')
                            ->schema([
                                Forms\Components\Section::make('Product Variants')
                                    ->description('Manage product variants with different sizes, colors, scents, etc.')
                                    ->schema([
                                        Forms\Components\Repeater::make('variants')
                                            ->relationship()
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Variant Name')
                                                    ->required()
                                                    ->maxLength(255),

                                                Forms\Components\TextInput::make('sku')
                                                    ->required()
                                                    ->maxLength(50)
                                                    ->unique(ignoreRecord: true),

                                                Forms\Components\TextInput::make('price')
                                                    ->numeric()
                                                    ->required()
                                                    ->prefix('Rs.'),

                                                Forms\Components\TextInput::make('discount_price')
                                                    ->numeric()
                                                    ->prefix('Rs.')
                                                    ->lt('price'),

                                                Forms\Components\Toggle::make('is_active')
                                                    ->default(true),
                                            ])
                                            ->columns(5)
                                            ->defaultItems(1)
                                            ->reorderable(false)
                                            ->addActionLabel('Add Variant'),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')
                    ->label('Image')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('brand.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('variants_count')
                    ->counts('variants')
                    ->label('Variants'),

                Tables\Columns\TextColumn::make('variants.price')
                    ->label('Price Range')
                    ->formatStateUsing(function ($record) {
                        $prices = $record->variants->pluck('price');
                        if ($prices->isEmpty()) {
                            return '-';
                        }
                        $min = number_format($prices->min(), 2);
                        $max = number_format($prices->max(), 2);
                        return $min === $max ? "Rs. {$min}" : "Rs. {$min} - {$max}";
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name'),

                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
