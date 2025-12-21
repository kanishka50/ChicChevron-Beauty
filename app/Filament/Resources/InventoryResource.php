<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Inventory Details')
                    ->schema([
                        Forms\Components\Select::make('product_variant_id')
                            ->relationship('variant', 'sku')
                            ->getOptionLabelFromRecordUsing(fn ($record) =>
                                "{$record->product->name} - {$record->display_name} ({$record->sku})")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($record) => $record !== null),

                        Forms\Components\TextInput::make('stock_quantity')
                            ->numeric()
                            ->required()
                            ->default(0),

                        Forms\Components\TextInput::make('reserved_quantity')
                            ->numeric()
                            ->required()
                            ->default(0)
                            ->disabled(),

                        Forms\Components\TextInput::make('low_stock_threshold')
                            ->numeric()
                            ->required()
                            ->default(5),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('variant.product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('variant.display_name')
                    ->label('Variant')
                    ->searchable(),

                Tables\Columns\TextColumn::make('variant.sku')
                    ->label('SKU')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->sortable(),

                Tables\Columns\TextColumn::make('reserved_quantity')
                    ->label('Reserved')
                    ->sortable(),

                Tables\Columns\TextColumn::make('available_stock')
                    ->label('Available')
                    ->getStateUsing(fn ($record) => $record->stock_quantity - $record->reserved_quantity)
                    ->badge()
                    ->color(fn ($state) =>
                        $state <= 0 ? 'danger' :
                        ($state <= 5 ? 'warning' : 'success')),

                Tables\Columns\TextColumn::make('low_stock_threshold')
                    ->label('Low Stock Alert')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('stock_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'out-of-stock' => 'danger',
                        'critical' => 'danger',
                        'low' => 'warning',
                        default => 'success',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'low' => 'Low Stock',
                        'out' => 'Out of Stock',
                        'in' => 'In Stock',
                    ])
                    ->query(fn (Builder $query, array $data) => match ($data['value']) {
                        'low' => $query->whereRaw('stock_quantity - reserved_quantity <= low_stock_threshold')
                                       ->whereRaw('stock_quantity - reserved_quantity > 0'),
                        'out' => $query->whereRaw('stock_quantity - reserved_quantity <= 0'),
                        'in' => $query->whereRaw('stock_quantity - reserved_quantity > 0'),
                        default => $query,
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('addStock')
                    ->label('Add Stock')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->label('Quantity to Add'),

                        Forms\Components\TextInput::make('cost_per_unit')
                            ->numeric()
                            ->required()
                            ->prefix('Rs.')
                            ->label('Cost per Unit'),

                        Forms\Components\TextInput::make('supplier')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('notes')
                            ->rows(2),
                    ])
                    ->action(function (Inventory $record, array $data) {
                        InventoryMovement::create([
                            'product_variant_id' => $record->product_variant_id,
                            'type' => InventoryMovement::TYPE_IN,
                            'quantity' => $data['quantity'],
                            'cost_per_unit' => $data['cost_per_unit'],
                            'supplier' => $data['supplier'],
                            'notes' => $data['notes'],
                        ]);

                        $record->increment('stock_quantity', $data['quantity']);

                        Notification::make()
                            ->success()
                            ->title('Stock added successfully')
                            ->body("Added {$data['quantity']} units to inventory.")
                            ->send();
                    }),

                Tables\Actions\Action::make('adjust')
                    ->label('Adjust')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->color('warning')
                    ->form([
                        Forms\Components\TextInput::make('new_quantity')
                            ->numeric()
                            ->required()
                            ->label('New Stock Quantity'),

                        Forms\Components\Textarea::make('notes')
                            ->required()
                            ->label('Reason for adjustment'),
                    ])
                    ->action(function (Inventory $record, array $data) {
                        $difference = $data['new_quantity'] - $record->stock_quantity;

                        InventoryMovement::create([
                            'product_variant_id' => $record->product_variant_id,
                            'type' => InventoryMovement::TYPE_ADJUSTMENT,
                            'quantity' => abs($difference),
                            'notes' => ($difference >= 0 ? '+' : '-') . abs($difference) . ': ' . $data['notes'],
                        ]);

                        $record->update(['stock_quantity' => $data['new_quantity']]);

                        Notification::make()
                            ->success()
                            ->title('Stock adjusted')
                            ->body("Stock adjusted by {$difference} units.")
                            ->send();
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('stock_quantity', 'asc');
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereRaw('stock_quantity - reserved_quantity <= low_stock_threshold')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
