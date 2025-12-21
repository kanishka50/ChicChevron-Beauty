<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintResource\Pages;
use App\Models\Complaint;
use App\Models\ComplaintResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Complaint Details')
                    ->schema([
                        Forms\Components\TextInput::make('complaint_number')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'open' => 'Open',
                                'in_progress' => 'In Progress',
                                'resolved' => 'Resolved',
                                'closed' => 'Closed',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('user.name')
                            ->label('Customer')
                            ->disabled(),

                        Forms\Components\TextInput::make('complaint_type')
                            ->label('Type')
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'product_not_received' => 'Product Not Received',
                                'wrong_product' => 'Wrong Product Delivered',
                                'damaged_product' => 'Damaged Product',
                                'other' => 'Other Issue',
                                default => ucfirst($state),
                            })
                            ->disabled(),

                        Forms\Components\TextInput::make('subject')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->disabled()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('complaint_number')
                    ->label('Complaint #')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn ($record) => $record->user->email ?? ''),

                Tables\Columns\TextColumn::make('complaint_type')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'product_not_received' => 'Product Not Received',
                        'wrong_product' => 'Wrong Product',
                        'damaged_product' => 'Damaged Product',
                        'other' => 'Other',
                        default => ucfirst($state),
                    })
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('subject')
                    ->limit(40)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->subject),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'open' => 'danger',
                        'in_progress' => 'warning',
                        'resolved' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),

                Tables\Columns\TextColumn::make('responses_count')
                    ->counts('responses')
                    ->label('Responses')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Filed On')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    ]),

                Tables\Filters\SelectFilter::make('complaint_type')
                    ->label('Type')
                    ->options([
                        'product_not_received' => 'Product Not Received',
                        'wrong_product' => 'Wrong Product',
                        'damaged_product' => 'Damaged Product',
                        'other' => 'Other',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('info'),

                Tables\Actions\Action::make('markInProgress')
                    ->label('In Progress')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'open')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => 'in_progress']);

                        Notification::make()
                            ->success()
                            ->title('Complaint marked as in progress')
                            ->send();
                    }),

                Tables\Actions\Action::make('resolve')
                    ->label('Resolve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => in_array($record->status, ['open', 'in_progress']))
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => 'resolved']);

                        Notification::make()
                            ->success()
                            ->title('Complaint resolved')
                            ->send();
                    }),

                Tables\Actions\Action::make('close')
                    ->label('Close')
                    ->icon('heroicon-o-x-circle')
                    ->color('gray')
                    ->visible(fn ($record) => $record->status === 'resolved')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => 'closed']);

                        Notification::make()
                            ->success()
                            ->title('Complaint closed')
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
                Infolists\Components\Section::make('Complaint Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('complaint_number')
                            ->label('Complaint #'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'open' => 'danger',
                                'in_progress' => 'warning',
                                'resolved' => 'success',
                                'closed' => 'gray',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),
                        Infolists\Components\TextEntry::make('complaint_type')
                            ->label('Type')
                            ->formatStateUsing(fn ($state) => match ($state) {
                                'product_not_received' => 'Product Not Received',
                                'wrong_product' => 'Wrong Product Delivered',
                                'damaged_product' => 'Damaged Product',
                                'other' => 'Other Issue',
                                default => ucfirst($state),
                            }),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Filed On')
                            ->dateTime('M d, Y h:i A'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Customer Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Name'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email'),
                        Infolists\Components\TextEntry::make('user.phone')
                            ->label('Phone')
                            ->placeholder('—'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Complaint Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('subject')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull()
                            ->prose(),
                    ]),

                Infolists\Components\Section::make('Related Order')
                    ->schema([
                        Infolists\Components\TextEntry::make('order.order_number')
                            ->label('Order #')
                            ->url(fn ($record) => $record->order_id
                                ? OrderResource::getUrl('view', ['record' => $record->order_id])
                                : null)
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('order.created_at')
                            ->label('Order Date')
                            ->dateTime('M d, Y'),
                        Infolists\Components\TextEntry::make('order.status')
                            ->label('Order Status')
                            ->badge()
                            ->color(fn ($state) => match ($state) {
                                'processing' => 'info',
                                'shipping' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn ($state) => ucfirst($state ?? '')),
                        Infolists\Components\TextEntry::make('order.total_amount')
                            ->label('Order Total')
                            ->money('LKR'),
                    ])
                    ->columns(4)
                    ->visible(fn ($record) => $record->order_id !== null),

                Infolists\Components\Section::make('Conversation History')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('responses')
                            ->schema([
                                Infolists\Components\TextEntry::make('sender')
                                    ->label('')
                                    ->state(fn ($record) => $record->is_admin_response
                                        ? ($record->admin->name ?? 'Admin')
                                        : ($record->user->name ?? 'Customer'))
                                    ->badge()
                                    ->color(fn ($record) => $record->is_admin_response ? 'primary' : 'gray'),
                                Infolists\Components\TextEntry::make('message')
                                    ->label('')
                                    ->columnSpan(2),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('')
                                    ->dateTime('M d, Y h:i A')
                                    ->color('gray'),
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
            'index' => Pages\ListComplaints::route('/'),
            'view' => Pages\ViewComplaint::route('/{record}'),
            'edit' => Pages\EditComplaint::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'open')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
