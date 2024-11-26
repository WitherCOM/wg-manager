<?php

namespace App\Filament\Resources\SubnetResource\Pages;

use App\Filament\Resources\SubnetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubnets extends ListRecords
{
    protected static string $resource = SubnetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
