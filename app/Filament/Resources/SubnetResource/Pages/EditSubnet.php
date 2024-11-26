<?php

namespace App\Filament\Resources\SubnetResource\Pages;

use App\Filament\Resources\SubnetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubnet extends EditRecord
{
    protected static string $resource = SubnetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
