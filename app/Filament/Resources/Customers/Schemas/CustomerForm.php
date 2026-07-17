<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;



class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Information')
                    ->schema([
                        TextInput::make('customer_code')
                            ->label('Customer Code')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto Generated'),

                        TextInput::make('name')
                            ->label('Customer Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(20),

                        TextInput::make('address')
                            ->maxLength(255),

                        TextInput::make('city')
                            ->maxLength(100),

                        TextInput::make('country')
                            ->default('Kenya')
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),

                    ])
                    ->columns(2),
            ]);
    }
}
