<?php

namespace App\Filament\Admin\Resources\PengeluaranResource\Pages;

use App\Filament\Admin\Resources\PengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPengeluaran extends EditRecord
{
    protected static string $resource = PengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
