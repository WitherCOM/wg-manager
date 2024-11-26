<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubnetResource\Pages;
use App\Filament\Resources\SubnetResource\RelationManagers\PeersRelationManager;
use App\Models\Subnet;
use App\Rules\SubnetCollisionRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubnetResource extends Resource
{
    protected static ?string $model = Subnet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Fieldset::make(__('subnet.cidr'))
                    ->schema([
                        Forms\Components\TextInput::make('network')
                            ->required()
                            ->ipv4()
                            ->rule(function (Forms\Components\TextInput $component) {
                                return new SubnetCollisionRule($component->getRecord());
                            })
                            ->columnSpan(4),
                        Forms\Components\TextInput::make('mask')
                            ->required()
                            ->integer()
                            ->minValue(10)
                            ->maxValue(30),
                        Forms\Components\TextInput::make('port')
                            ->required()
                            ->integer()
                            ->unique(ignoreRecord: true)
                            ->minValue(30001)
                            ->maxValue(30010)
                    ])
                ->columns(5),
                Forms\Components\TextInput::make('preshared_key')
                    ->password()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('cidr'),
                Tables\Columns\TextColumn::make('port'),
                Tables\Columns\TextColumn::make('gateway')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PeersRelationManager::make()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubnets::route('/'),
            'create' => Pages\CreateSubnet::route('/create'),
            'edit' => Pages\EditSubnet::route('/{record}/edit'),
        ];
    }
}
