<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Models\Sale;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use App\Services\SalesExportService;
use App\Services\SalesReportService;

class SalesReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $title = 'Sales Report';

    protected static ?string $navigationLabel = 'Sales Report';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.sales-report';

    public array $filters = [];

    public function mount(): void
    {
        $this->form->fill([
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->toDateString(),
            'status' => null,
            'customer' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('filters')
            ->components([
                Grid::make(4)
                    ->schema([

                        DatePicker::make('from')
                            ->label('From')
                            ->native(false),

                        DatePicker::make('to')
                            ->label('To')
                            ->native(false),

                        Select::make('status')
                            ->placeholder('All Statuses')
                            ->options([
                                'Draft' => 'Draft',
                                'Completed' => 'Completed',
                                'Cancelled' => 'Cancelled',
                            ]),

                        Select::make('customer')
                            ->placeholder('All Customers')
                            ->searchable()
                            ->options(
                                Customer::query()
                                    ->orderBy('name')
                                    ->pluck('name', 'id')
                            ),

                    ]),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return Sale::query()
            ->with('customer')
            ->when(
                filled($this->filters['from'] ?? null),
                fn (Builder $query) => $query->whereDate(
                    'sale_date',
                    '>=',
                    Carbon::parse($this->filters['from'])
                )
            )
            ->when(
                filled($this->filters['to'] ?? null),
                fn (Builder $query) => $query->whereDate(
                    'sale_date',
                    '<=',
                    Carbon::parse($this->filters['to'])
                )
            )
            ->when(
                filled($this->filters['status'] ?? null),
                fn (Builder $query) => $query->where(
                    'status',
                    $this->filters['status']
                )
            )
            ->when(
                filled($this->filters['customer'] ?? null),
                fn (Builder $query) => $query->where(
                    'customer_id',
                    $this->filters['customer']
                )
            );
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())

            ->defaultSort('sale_date', 'desc')

            ->columns([

                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->placeholder('Walk-in Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sale_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subtotal')
                    ->money('KES')
                    ->alignEnd()
                    ->sortable(),

                Tables\Columns\TextColumn::make('tax')
                    ->money('KES')
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('discount')
                    ->money('KES')
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('KES')
                    ->weight('bold')
                    ->color('success')
                    ->alignEnd()
                    ->sortable(),

            ])

            ->paginated([10, 25, 50, 100])

            ->striped()

            ->emptyStateHeading('No sales found')

            ->emptyStateDescription('Try adjusting your filters.')

            ->headerActions([

                Action::make('excel')
            ->label('Export Excel')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {

                $file = app(SalesExportService::class)
                    ->export($this->filters);


                return response()
                    ->download($file)
                    ->deleteFileAfterSend();

            }),

                Action::make('pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document')
                    ->action(function () {

                        $this->filters = $this->form->getState();


                        return response()->streamDownload(
                            function () {

                                echo app(SalesReportService::class)
                                    ->generatePdf($this->filters)
                                    ->output();

                            },
                            'sales-report.pdf'
                        );

                    }),

            ]);
    }
    public function generateReport(): void
    {

        $this->resetTable();
    }
    public function resetFilters(): void
    {
        $this->filters = [
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->toDateString(),
            'status' => null,
            'customer_id' => null,
        ];

        $this->form->fill($this->filters);

        $this->resetTable();
    }
}