<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Product;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use UnitEnum;
use App\Services\ProfitReportService;
use Filament\Tables;
use Filament\Tables\Table;
use App\Services\ProfitExportService;
use Filament\Actions\Action;

class ProfitReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $title = 'Profit Report';

    protected static ?string $navigationLabel = 'Profit Report';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.profit-report';

    public array $filters = [];

    public array $totals = [];

    public function mount(): void
    {
        $this->filters = [
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->toDateString(),
            'status' => 'Completed',
            'customer' => null,
            'product' => null,
        ];

        $this->form->fill($this->filters);

        $this->loadTotals();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('filters')
            ->components([

                Grid::make(5)
                    ->schema([

                        DatePicker::make('from')
                            ->label('From')
                            ->native(false),

                        DatePicker::make('to')
                            ->label('To')
                            ->native(false),

                        Select::make('customer')
                            ->placeholder('All Customers')
                            ->searchable()
                            ->options(
                                Customer::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            ),

                        Select::make('product')
                            ->placeholder('All Products')
                            ->searchable()
                            ->options(
                                Product::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            ),

                        Select::make('status')
                            ->options([
                                'Draft' => 'Draft',
                                'Completed' => 'Completed',
                                'Cancelled' => 'Cancelled',
                            ])
                            ->default('Completed'),

                    ]),

            ]);
    }

    protected function loadTotals(): void
    {
        $this->totals = app(\App\Services\ProfitReportService::class)
            ->totals($this->filters);
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(
                app(ProfitReportService::class)
                    ->query($this->filters)
            )

            ->defaultSort('sale_id', 'desc')

            ->columns([

                Tables\Columns\TextColumn::make('sale.invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sale.sale_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sale.customer.name')
                    ->label('Customer')
                    ->placeholder('Walk-in Customer')
                    ->searchable(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Unit Price')
                    ->money('KES')
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('cost_price')
                    ->label('Cost Price')
                    ->money('KES')
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('revenue')
                    ->label('Revenue')
                    ->state(fn ($record) => $record->quantity * $record->unit_price)
                    ->money('KES')
                    ->alignEnd()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('cost')
                    ->label('Cost')
                    ->state(fn ($record) => $record->quantity * $record->cost_price)
                    ->money('KES')
                    ->alignEnd()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('profit')
                    ->label('Profit')
                    ->state(fn ($record) => ($record->quantity * $record->unit_price) - ($record->quantity * $record->cost_price))
                    ->money('KES')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                    ->weight('bold')
                    ->alignEnd()
                    ->sortable(),

            ])

            ->striped()

            ->paginated([10, 25, 50, 100])

            ->emptyStateHeading('No profit records found')

            ->emptyStateDescription('Try adjusting your filters.')
            ->headerActions([
                Action::make('excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {

                        $file = app(ProfitExportService::class)
                            ->exportExcel($this->filters);

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

                                echo app(ProfitExportService::class)
                                    ->exportPdf($this->filters)
                                    ->output();

                            },

                            'profit-report.pdf'

                        );

                    }),

            ]);
    }
    public function generateReport(): void
    {
        $this->filters = $this->form->getState();

        $this->loadTotals();

        $this->resetTable();
    }
    public function resetFilters(): void
    {
        $this->filters = [

            'from' => now()->startOfMonth()->toDateString(),

            'to' => now()->toDateString(),

            'status' => 'Completed',

            'customer' => null,

            'product' => null,

        ];

        $this->form->fill($this->filters);

        $this->loadTotals();

        $this->resetTable();
    }
}