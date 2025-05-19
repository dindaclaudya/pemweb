<?php

namespace App\Filament\Admin\Resources\PengeluaranResource\Pages;

use App\Filament\Admin\Resources\PengeluaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPengeluarans extends ListRecords
{
    protected static string $resource = PengeluaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
