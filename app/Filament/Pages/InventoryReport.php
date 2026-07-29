<?php

namespace App\Filament\Pages;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\InventoryExportService;
use App\Services\InventoryReportService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class InventoryReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $title = 'Inventory Report';

    protected static ?string $navigationLabel = 'Inventory Report';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.inventory-report';

    public array $filters = [];

    public function mount(): void
    {
        $this->form->fill([
            'warehouse' => null,
            'category' => null,
            'supplier' => null,
            'status' => null,
            'product' => null,
            'sku' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('filters')
            ->components([
                Grid::make(3)
                    ->schema([

                        Select::make('warehouse')
                            ->label('Warehouse')
                            ->placeholder('All Warehouses')
                            ->searchable()
                            ->options(
                                Warehouse::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            ),

                        Select::make('category')
                            ->label('Category')
                            ->placeholder('All Categories')
                            ->searchable()
                            ->options(
                                Category::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            ),

                        Select::make('supplier')
                            ->label('Supplier')
                            ->placeholder('All Suppliers')
                            ->searchable()
                            ->options(
                                Supplier::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            ),

                        Select::make('status')
                            ->placeholder('All Statuses')
                            ->options([
                                'in_stock' => 'In Stock',
                                'low_stock' => 'Low Stock',
                                'out_of_stock' => 'Out of Stock',
                                'overstocked' => 'Overstocked',
                            ]),

                        TextInput::make('product')
                            ->placeholder('Search Product'),

                        TextInput::make('sku')
                            ->placeholder('Search SKU'),

                    ]),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return app(InventoryReportService::class)
            ->query($this->filters);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())

            ->defaultSort('name')

            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('barcode')
                    ->searchable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->sortable(),

                Tables\Columns\TextColumn::make('cost_price')
                    ->label('Cost Price')
                    ->money('KES')
                    ->alignEnd()
                    ->sortable(),

                Tables\Columns\TextColumn::make('selling_price')
                    ->label('Selling Price')
                    ->money('KES')
                    ->alignEnd()
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('Current Stock')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('minimum_stock')
                    ->label('Min')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('maximum_stock')
                    ->label('Max')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('stock_value')
                    ->label('Stock Value')
                    ->state(fn (Product $record) => $record->stock_quantity * $record->cost_price)
                    ->money('KES')
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->state(fn (Product $record) => app(InventoryReportService::class)->stockStatus($record))
                    ->color(fn (string $state) => match ($state) {
                        'In Stock' => 'success',
                        'Low Stock' => 'warning',
                        'Out of Stock' => 'danger',
                        'Overstocked' => 'info',
                        default => 'gray',
                    }),

            ])

            ->striped()

            ->paginated([10, 25, 50, 100])

            ->emptyStateHeading('No inventory found')

            ->emptyStateDescription('Try adjusting your filters.')

            ->headerActions([

                Action::make('excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {

                        $file = app(InventoryExportService::class)
                            ->export($this->filters);

                        return response()
                            ->download($file)
                            ->deleteFileAfterSend();

                    }),

                Action::make('pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document')
                    ->action(function () {

                        return response()->streamDownload(
                            function () {

                                echo app(InventoryReportService::class)
                                    ->generatePdf($this->filters)
                                    ->output();

                            },
                            'inventory-report.pdf'
                        );

                    }),

            ]);
    }

    public function summary(): array
    {
        return app(InventoryReportService::class)
            ->summary($this->filters);
    }

    public function generateReport(): void
    {
        $this->resetTable();
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'warehouse' => null,
            'category' => null,
            'supplier' => null,
            'status' => null,
            'product' => null,
            'sku' => null,
        ];

        $this->form->fill($this->filters);

        $this->resetTable();
    }
}