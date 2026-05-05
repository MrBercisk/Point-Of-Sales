<?php

namespace App\Filament\Resources\Topup\Pages;

use App\Filament\Resources\Topup\TopUpResource;
use App\Models\Student;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTopUp extends CreateRecord
{
    protected static string $resource = TopUpResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Top up berhasil disimpan!';
    }

    protected function handleRecordCreation(array $data): Model
    {
        $student = Student::findOrFail($data['student_id']);

        // topUp() di model Student sudah handle:
        // - increment balance
        // - catat wallet_transaction (type, amount, balance_before, balance_after)
        return $student->topUp(
            amount: (float) $data['amount'],
            userId: (int) $data['user_id'],
            note:   $data['note'] ?? null,
        );
    }

    /**
     * Mutate data sebelum create — buang field helper (_current_balance, dll)
     * agar tidak ikut masuk ke handleRecordCreation dengan data kotor
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Buang field placeholder yang hanya untuk UI
        unset($data['_current_balance'], $data['_balance_after_preview']);

        return $data;
    }
}