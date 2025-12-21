<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\InventoryResource;
use App\Models\Inventory;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockAlert extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Low Stock Alerts')
            ->description('Products that need restocking')
            ->query(
                Inventory::query()
                    ->with('variant.product')
                    ->whereRaw('stock_quantity - reserved_quantity <= low_stock_threshold')
                    ->orderByRaw('stock_quantity - reserved_quantity ASC')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('variant.product.name')
                    ->label('Product')
                    ->limit(25),

                Tables\Columns\TextColumn::make('variant.display_name')
                    ->label('Variant'),

                Tables\Columns\TextColumn::make('variant.sku')
                    ->label('SKU')
                    ->copyable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Stock'),

                Tables\Columns\TextColumn::make('reserved_quantity')
                    ->label('Reserved'),

                Tables\Columns\TextColumn::make('available')
                    ->label('Available')
                    ->getStateUsing(fn ($record) => $record->stock_quantity - $record->reserved_quantity)
                    ->badge()
                    ->color(fn ($state) => $state <= 0 ? 'danger' : 'warning'),
            ])
            ->actions([
                Tables\Actions\Action::make('addStock')
                    ->label('Add Stock')
                    ->icon('heroicon-o-plus')
                    ->url(fn ($record) => InventoryResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
