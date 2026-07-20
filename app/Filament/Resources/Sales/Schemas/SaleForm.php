<?php

namespace App\Filament\Resources\Sales\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sale Information')
                    ->schema([
                        TextInput::make('invoice_number')
                            ->label('Invoice Number')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Auto Generated'),

                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('sale_date')
                            ->default(now())
                            ->required(),

                        Select::make('status')
                            ->options([
                                'Draft' => 'Draft',
                                'Completed' => 'Completed',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->default('Draft')
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Sale Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            })
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if (! $state) {
                                            $set('unit_price', 0);
                                            $set('line_total', 0);

                                            return;
                                        }

                                        $product = Product::find($state);

                                        if (! $product) {
                                            return;
                                        }

                                        $quantity = (float) ($get('quantity') ?? 1);
                                        $unitPrice = (float) $product->selling_price;

                                        $set('unit_price', $unitPrice);
                                        $set('line_total', $quantity * $unitPrice);
                                        self::updateTotals($get, $set);
                                    }),

                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $quantity = (float) ($state ?: 0);
                                        $unitPrice = (float) ($get('unit_price') ?: 0);

                                        $set('line_total', $quantity * $unitPrice);

                                        self::updateTotals($get, $set);
                                    }),

                                TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                        $unitPrice = (float) ($state ?: 0);
                                        $quantity = (float) ($get('quantity') ?: 0);

                                        $set('line_total', $quantity * $unitPrice);

                                        self::updateTotals($get, $set);
                                    }),

                                TextInput::make('line_total')
                                    ->label('Line Total')
                                    ->numeric()
                                    ->prefix('KES')
                                    ->readOnly()
                                    ->dehydrated(),
                            ])
                            ->columns([
                                'md' => 4,
                                'xl' => 4,
                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Sale Totals')
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('KES')
                            ->readOnly(),

                        TextInput::make('discount')
                            ->label('Discount')
                            ->numeric()
                            ->prefix('KES')
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            }),

                        TextInput::make('tax')
                            ->label('Tax')
                            ->numeric()
                            ->prefix('KES')
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::updateTotals($get, $set);
                            }),

                        TextInput::make('total_amount')
                            ->label('Grand Total')
                            ->numeric()
                            ->prefix('KES')
                            ->readOnly(),
                    ])
                    ->columns(4)
                    ->columnSpanFull(),
            ]);
    }
    protected static function updateTotals(Get $get, Set $set): void
    {
        $subtotal = collect($get('items') ?? [])
            ->sum(function ($item): float {
                if (! is_array($item)) {
                    return 0;
                }

                return (float) ($item['line_total'] ?? 0);
            });

        $discount = (float) ($get('discount') ?: 0);
        $tax = (float) ($get('tax') ?: 0);

        $set('subtotal', $subtotal);

        $set('total_amount', ($subtotal - $discount) + $tax);
    }
}