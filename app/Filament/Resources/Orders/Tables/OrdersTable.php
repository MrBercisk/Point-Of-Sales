<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class OrdersTable
{
  public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->copyMessage('Invoice copied!')
                    ->icon('heroicon-o-document-text'),

                TextColumn::make('customer_name')
                    ->default('Walk-in Customer')
                    ->searchable()
                    ->icon('heroicon-o-user'),

                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->badge()
                    ->color('info'),

                TextColumn::make('total_amount')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match($state) {
                        'pending' => 'heroicon-o-clock',
                        'completed' => 'heroicon-o-check-circle',
                        'cancelled' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->description(fn (Order $record): string =>
                        $record->created_at->diffForHumans()
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->native(false),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('From Date'),
                        DatePicker::make('until')->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'],
                                fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date)
                            )
                            ->when($data['until'],
                                fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['from'] ?? null) {
                            $indicators['from'] = 'From: ' . Carbon::parse($data['from'])->format('d M Y');
                        }
                        if ($data['until'] ?? null) {
                            $indicators['until'] = 'Until: ' . Carbon::parse($data['until'])->format('d M Y');
                        }
                        return $indicators;
                    }),

                Filter::make('today')
                    ->label('Today')
                    ->query(fn (Builder $query) => $query->whereDate('created_at', today()))
                    ->toggle(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->visible(fn (Order $record) => $record->status === 'pending'),

                    Action::make('complete')
                        ->label('Mark Complete')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Complete Order')
                        ->modalDescription('Are you sure you want to mark this order as completed?')
                        ->visible(fn (Order $record) => $record->status === 'pending')
                        ->action(function (Order $record) {
                            $record->update(['status' => 'completed']);
                            Notification::make()
                                ->title('Order Completed')
                                ->body("Order {$record->invoice_number} has been completed.")
                                ->success()
                                ->send();
                        }),

                    Action::make('cancel')
                        ->label('Cancel Order')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Cancel Order')
                        ->modalDescription('Are you sure? Stock will be restored.')
                        ->visible(fn (Order $record) => $record->status === 'pending')
                        ->action(function (Order $record) {
                            $record->markAsCancelled();
                            Notification::make()
                                ->title('Order Cancelled')
                                ->body("Order {$record->invoice_number} has been cancelled. Stock restored.")
                                ->warning()
                                ->send();
                        }),

                    Action::make('print')
                        ->label('Print Invoice')
                        ->icon('heroicon-o-printer')
                        ->color('gray')
                        ->url(fn (Order $record) => route('invoice.print', $record))
                        ->openUrlInNewTab()
                        ->visible(false),

                    DeleteAction::make()
                        ->visible(fn (Order $record) => $record->status === 'cancelled'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('complete_all')
                        ->label('Complete Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each(
                            fn ($record) => $record->status === 'pending' && $record->update(['status' => 'completed'])
                        ))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->striped()
            ->poll('30s');
    }
}
