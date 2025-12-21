<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryMovementResource\Pages;
use App\Models\InventoryMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InventoryMovementResource extends Resource
{
    protected static ?string $model = InventoryMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?string $navigationLabel = 'Stock History';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Movement Details')
                    ->schema([
                        Forms\Components\Select::make('product_variant_id')
                            ->relationship('variant', 'sku')
                            ->disabled(),

                        Forms\Components\TextInput::make('type')
                            ->disabled(),

                        Forms\Components\TextInput::make('quantity')
                            ->disabled(),

                        Forms\Components\TextInput::make('cost_per_unit')
                            ->disabled(),

                        Forms\Components\TextInput::make('supplier')
                            ->disabled(),

                        Forms\Components\Textarea::make('notes')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('variant.product.name')
                    ->label('Product')
                    ->searchable()
                    ->limit(20),

                Tables\Columns\TextColumn::make('variant.display_name')
                    ->label('Variant')
                    ->searchable(),

                Tables\Columns\TextColumn::make('variant.sku')
                    ->label('SKU')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        InventoryMovement::TYPE_IN => 'success',
                        InventoryMovement::TYPE_RESERVED => 'warning',
                        InventoryMovement::TYPE_SOLD => 'danger',
                        InventoryMovement::TYPE_RELEASED => 'info',
                        InventoryMovement::TYPE_ADJUSTMENT => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        InventoryMovement::TYPE_IN => 'Stock In',
                        InventoryMovement::TYPE_RESERVED => 'Reserved',
                        InventoryMovement::TYPE_SOLD => 'Sold',
                        InventoryMovement::TYPE_RELEASED => 'Released',
                        InventoryMovement::TYPE_ADJUSTMENT => 'Adjustment',
                        default => ucfirst($state),
                    }),

                Tables\Columns\TextColumn::make('quantity')
                    ->sortable(),

                Tables\Columns\TextColumn::make('cost_per_unit')
                    ->money('LKR')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('supplier')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('order_id')
                    ->label('Order #')
                    ->url(fn ($record) => $record->order_id
                        ? OrderResource::getUrl('view', ['record' => $record->order_id])
                        : null)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('notes')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->notes),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        InventoryMovement::TYPE_IN => 'Stock In',
                        InventoryMovement::TYPE_RESERVED => 'Reserved',
                        InventoryMovement::TYPE_SOLD => 'Sold',
                        InventoryMovement::TYPE_RELEASED => 'Released',
                        InventoryMovement::TYPE_ADJUSTMENT => 'Adjustment',
                    ]),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListInventoryMovements::route('/'),
            'view' => Pages\ViewInventoryMovement::route('/{record}'),
        ];
    }
}
