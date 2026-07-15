<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Supplier Information")
                    ->schema([
                        
                        TextInput::make("supplier_code")
                            ->label("Supplier Code")
                            ->disabled()
                            ->dehydrated()
                            ->visible(fn (string $operation) => $operation !== 'create'),

                        TextInput::make("company_name")
                            ->label("Company Name")
                            ->maxLength(255)
                            ->required(),

                        TextInput::make("contact_person")
                            ->label("Contact Person")
                            ->maxLength(255)
                            ->required(),

                        TextInput::make("email")
                            ->label("Email")
                            ->email(),
                        
                        TextInput::make("phone")
                            ->label("Phone Number")
                            ->tel()
                            ->required(),

                        Textarea::make("address")
                            ->label("Address")
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->required(),

                        TextInput::make("city")
                            ->label("City")
                            ->maxLength(255)
                            ->required(),
                        TextInput::make("country")
                            ->default("Kenya"),

                        TextInput::Make('tax_number')
                            ->label('Tax Number')
                            ->maxLength(255)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Is Active')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}
