<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings as Settings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class GeneralSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = Settings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('wireguard_ip')
                    ->required()
                    ->ipv4()
            ]);
    }
}
