<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return false;
    }

    // Allow admins to view/edit orders without policy restrictions
    public static function canView($record): bool
    {
        return true;
    }

    public static function canEdit($record): bool
    {
        return true;
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Details')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'processing' => 'Processing',
                                'shipping' => 'Shipping',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('total_amount')
                            ->prefix('Rs.')
                            ->disabled(),

                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ])
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('shipping_name')
                            ->label('Name'),

                        Forms\Components\TextInput::make('customer_email')
                            ->email(),

                        Forms\Components\TextInput::make('shipping_phone')
                            ->label('Phone'),

                        Forms\Components\Textarea::make('full_shipping_address')
                            ->label('Address')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('shipping_name')
                    ->label('Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->money('LKR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'processing' => 'info',
                        'shipping' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'processing' => 'Processing',
                        'shipping' => 'Shipping',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
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
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('info'),

                Tables\Actions\Action::make('markShipping')
                    ->label('Ship')
                    ->icon('heroicon-o-truck')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'processing')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $adminId = auth()->id();
                        $record->updateStatus('shipping', 'Order shipped', $adminId);

                        Notification::make()
                            ->success()
                            ->title('Order marked as shipping')
                            ->send();
                    }),

                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'shipping')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $adminId = auth()->id();
                        DB::transaction(function () use ($record, $adminId) {
                            // Convert reserved stock to sold
                            foreach ($record->items as $item) {
                                InventoryMovement::create([
                                    'product_variant_id' => $item->product_variant_id,
                                    'type' => InventoryMovement::TYPE_SOLD,
                                    'quantity' => $item->quantity,
                                    'order_id' => $record->id,
                                ]);

                                $inventory = Inventory::where('product_variant_id', $item->product_variant_id)->first();
                                if ($inventory) {
                                    $inventory->decrement('stock_quantity', $item->quantity);
                                    $inventory->decrement('reserved_quantity', $item->quantity);
                                }
                            }

                            $record->updateStatus('completed', 'Order completed', $adminId);
                        });

                        Notification::make()
                            ->success()
                            ->title('Order completed successfully')
                            ->send();
                    }),

                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn ($record) => in_array($record->status, ['processing', 'shipping']))
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Cancellation Reason')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $adminId = auth()->id();
                        DB::transaction(function () use ($record, $data, $adminId) {
                            // Release reserved stock
                            foreach ($record->items as $item) {
                                InventoryMovement::create([
                                    'product_variant_id' => $item->product_variant_id,
                                    'type' => InventoryMovement::TYPE_RELEASED,
                                    'quantity' => $item->quantity,
                                    'order_id' => $record->id,
                                    'notes' => 'Order cancelled: ' . $data['reason'],
                                ]);

                                $inventory = Inventory::where('product_variant_id', $item->product_variant_id)->first();
                                if ($inventory) {
                                    $inventory->decrement('reserved_quantity', $item->quantity);
                                }
                            }

                            $record->updateStatus('cancelled', $data['reason'], $adminId);
                        });

                        Notification::make()
                            ->warning()
                            ->title('Order cancelled')
                            ->body('Stock has been released back to inventory.')
                            ->send();
                    }),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_number')
                            ->label('Order #'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'processing' => 'info',
                                'shipping' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('payment_method')
                            ->formatStateUsing(fn ($state) => strtoupper($state)),
                        Infolists\Components\TextEntry::make('payment_status')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'completed' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime(),
                    ])
                    ->columns(5),

                Infolists\Components\Section::make('Customer Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('shipping_name')
                            ->label('Name'),
                        Infolists\Components\TextEntry::make('customer_email')
                            ->label('Email'),
                        Infolists\Components\TextEntry::make('shipping_phone')
                            ->label('Phone'),
                        Infolists\Components\TextEntry::make('full_shipping_address')
                            ->label('Address')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Order Items')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')
                                    ->label('Product'),
                                Infolists\Components\TextEntry::make('variant.display_name')
                                    ->label('Variant'),
                                Infolists\Components\TextEntry::make('quantity'),
                                Infolists\Components\TextEntry::make('unit_price')
                                    ->money('LKR'),
                                Infolists\Components\TextEntry::make('total_price')
                                    ->money('LKR'),
                            ])
                            ->columns(5),
                    ]),

                Infolists\Components\Section::make('Order Summary')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->money('LKR'),
                        Infolists\Components\TextEntry::make('discount_amount')
                            ->money('LKR'),
                        Infolists\Components\TextEntry::make('shipping_amount')
                            ->money('LKR'),
                        Infolists\Components\TextEntry::make('total_amount')
                            ->money('LKR')
                            ->weight('bold'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => !empty($record->notes)),

                Infolists\Components\Section::make('Status History')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('statusHistory')
                            ->schema([
                                Infolists\Components\TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn ($state) => match ($state) {
                                        'payment_completed' => 'success',
                                        'processing' => 'info',
                                        'shipping' => 'warning',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn ($state) => match ($state) {
                                        'payment_completed' => 'Payment Completed',
                                        'processing' => 'Processing',
                                        'shipping' => 'Shipping',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                        default => ucfirst($state),
                                    }),
                                Infolists\Components\TextEntry::make('comment')
                                    ->label('Comment')
                                    ->placeholder('—')
                                    ->color('gray'),
                                Infolists\Components\TextEntry::make('changedBy.name')
                                    ->label('Changed By')
                                    ->placeholder('System')
                                    ->icon('heroicon-o-user'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Date & Time')
                                    ->dateTime('M d, Y h:i A')
                                    ->icon('heroicon-o-calendar'),
                            ])
                            ->columns(4)
                            ->contained(false),
                    ])
                    ->collapsible(),
            ]);
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
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'processing')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
