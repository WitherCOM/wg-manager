<?php

namespace App\Filament\Resources\SubnetResource\RelationManagers;

use App\Models\Peer;
use App\Models\Subnet;
use App\Rules\WrongIpRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class PeersRelationManager extends RelationManager
{
    protected static string $relationship = 'peers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('ip_address')
                    ->required()
                    ->ipv4()
                    ->rule(new WrongIpRule($this->getOwnerRecord()))
                    ->afterStateHydrated(function (Forms\Components\TextInput $component, $state) {
                        // Generate ip address if it is create
                        if (is_null($state)) {
                            $component->state($this->getOwnerRecord()->allocateIp());
                        }
                    })
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->copyable()
                    ->copyableState(fn(Peer $record) => $record->config()->render()),
                Tables\Columns\TextColumn::make('ip_address')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('qr')
                    ->modal()
                    ->modalContent(fn(Peer $record) => new HtmlString("<img src='".$record->qr()."'>"))
                    ->modalSubmitAction(false)
                    ->icon('heroicon-o-qr-code'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
