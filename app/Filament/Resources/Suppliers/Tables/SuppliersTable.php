<?php

namespace App\Filament\Resources\Suppliers\Tables;

use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;



class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("supplier_code")
                    ->label("Supplier Code")
                    ->sortable()
                    ->searchable()
                    ->copyable()
                    ->searchable(),

                TextColumn::make("company_name")
                    ->label("Company Name")
                    ->sortable()
                    ->searchable(),

                TextColumn::make("contact_person")
                    ->label("Contact Person")
                    ->sortable()
                    ->searchable(),

                TextColumn::make("phone")
                    ->label("Phone Number")
                    ->sortable()
                    ->searchable(),

                TextColumn::make("city")
                    ->label("City")
                    ->sortable()
                    ->searchable(),

                IconColumn::make("is_active")
                    ->label("Active")
                    ->boolean()
                    ->sortable()
                    ->searchable(),

                TextColumn::make("created_at")
                    ->label("Created At")
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                \Filament\Tables\Filters\Filter::make('is_active')
                    ->label('Status'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
