<?php

namespace App\Filament\Resources\ContactinfoResource\Pages;

use App\Filament\Resources\ContactinfoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContactinfo extends EditRecord
{
    protected static string $resource = ContactinfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
