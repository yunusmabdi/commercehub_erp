<?php

namespace App\Filament\Pages;

use App\Models\Supplier;
use App\Services\PurchaseExportService;
use App\Services\PurchaseReportService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PurchaseReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $title = 'Purchase Report';

    protected static ?string $navigationLabel = 'Purchase Report';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.purchase-report';

    public array $filters = [];

    public function mount(): void
    {
        $this->form->fill([
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->toDateString(),
            'supplier' => null,
            'status' => null,
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
                            ->label('Date From')
                            ->native(false),

                        DatePicker::make('to')
                            ->label('Date To')
                            ->native(false),

                        Select::make('supplier')
                            ->label('Supplier')
                            ->placeholder('All Suppliers')
                            ->searchable()
                            ->options(
                                Supplier::query()
                                    ->orderBy('company_name')
                                    ->pluck('company_name', 'id')
                            ),

                        Select::make('status')
                            ->label('Purchase Status')
                            ->placeholder('All Statuses')
                            ->options([
                                'Pending' => 'Pending',
                                'Received' => 'Received',
                                'Cancelled' => 'Cancelled',
                            ]),

                    ]),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return app(PurchaseReportService::class)
            ->query($this->filters);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())

            ->defaultSort('purchase_date', 'desc')

            ->columns([

                Tables\Columns\TextColumn::make('purchase_number')
                    ->label('Purchase Number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.company_name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('purchase_date')
                    ->label('Purchase Date')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('KES')
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success')
                    ->sortable(),

            ])

            ->paginated([10, 25, 50, 100])

            ->striped()

            ->emptyStateHeading('No purchases found')

            ->emptyStateDescription('Try adjusting your filters.')

            ->headerActions([

                Action::make('excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {

                        $this->filters = $this->form->getState();

                        $file = app(PurchaseExportService::class)
                            ->exportExcel($this->filters);

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

                                echo app(PurchaseExportService::class)
                                    ->exportPdf($this->filters)
                                    ->output();

                            },
                            'purchase-report.pdf'
                        );
                    }),

            ]);
    }

    public function generateReport(): void
    {
        $this->filters = $this->form->getState();

        $this->resetTable();
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->toDateString(),
            'supplier' => null,
            'status' => null,
        ];

        $this->form->fill($this->filters);

        $this->resetTable();
    }
}