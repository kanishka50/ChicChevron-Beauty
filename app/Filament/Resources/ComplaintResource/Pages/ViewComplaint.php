<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use App\Filament\Resources\ComplaintResource;
use App\Models\ComplaintResponse;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewComplaint extends ViewRecord
{
    protected static string $resource = ComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('respond')
                ->label('Send Response')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->color('primary')
                ->form([
                    Textarea::make('message')
                        ->label('Admin Response')
                        ->required()
                        ->rows(4)
                        ->placeholder('Type your response here...'),
                ])
                ->action(function (array $data) {
                    ComplaintResponse::create([
                        'complaint_id' => $this->record->id,
                        'admin_id' => auth()->id(),
                        'message' => $data['message'],
                        'is_admin_response' => true,
                    ]);

                    // Auto-update status to in_progress if open
                    if ($this->record->status === 'open') {
                        $this->record->update(['status' => 'in_progress']);
                    }

                    Notification::make()
                        ->success()
                        ->title('Response sent successfully')
                        ->send();

                    $this->refreshFormData(['responses']);
                }),

            Actions\Action::make('updateStatus')
                ->label('Update Status')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->form([
                    \Filament\Forms\Components\Select::make('status')
                        ->options([
                            'open' => 'Open',
                            'in_progress' => 'In Progress',
                            'resolved' => 'Resolved',
                            'closed' => 'Closed',
                        ])
                        ->default(fn () => $this->record->status)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->record->update(['status' => $data['status']]);

                    Notification::make()
                        ->success()
                        ->title('Status updated to ' . ucwords(str_replace('_', ' ', $data['status'])))
                        ->send();

                    $this->refreshFormData(['status']);
                }),

            Actions\EditAction::make(),
        ];
    }
}
