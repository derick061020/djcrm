<?php

namespace App\Filament\Resources\FormatosResource\Pages;

use App\Filament\Resources\FormatosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFormatos extends EditRecord
{
    protected static string $resource = FormatosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
