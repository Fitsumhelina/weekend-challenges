<?php

namespace App\Filament\Resources\ContactinfoResource\Pages;

use App\Filament\Resources\ContactinfoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactinfos extends ListRecords
{
    protected static string $resource = ContactinfoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
