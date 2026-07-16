<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                Select::make('type')
                    ->options(['IN' => 'I n', 'OUT' => 'O u t'])
                    ->required(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                TextInput::make('reference_type')
                    ->required(),
                TextInput::make('reference_id')
                    ->numeric(),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
