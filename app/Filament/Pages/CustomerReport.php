<?php

namespace App\Filament\Pages;

use App\Models\Customer;
use App\Services\CustomerExportService;
use App\Services\CustomerReportService;
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
use UnitEnum;

class CustomerReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $title = 'Customer Report';

    protected static ?string $navigationLabel = 'Customer Report';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.customer-report';

    public array $filters = [];

    public function mount(): void
    {
        $this->form->fill([
            'from' => now()->startOfMonth()->toDateString(),
            'to' => now()->toDateString(),
            'customer' => null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('filters')
            ->components([
                Grid::make(3)
                    ->schema([

                        DatePicker::make('from')
                            ->label('From')
                            ->native(false),

                        DatePicker::make('to')
                            ->label('To')
                            ->native(false),

                        Select::make('customer')
                            ->label('Customer')
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

    public function table(Table $table): Table
    {
        return $table
            ->query(
                app(CustomerReportService::class)
                    ->query($this->filters)
            )

            ->defaultSort('created_at', 'desc')

            ->columns([

                Tables\Columns\TextColumn::make('customer_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('address')
                    ->limit(40)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered')
                    ->date('d M Y')
                    ->sortable(),

            ])

            ->paginated([10, 25, 50, 100])

            ->striped()

            ->emptyStateHeading('No customers found')

            ->emptyStateDescription('Try adjusting your filters.')

            ->headerActions([

                Action::make('excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {

                        $file = app(CustomerExportService::class)
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

                                echo app(CustomerExportService::class)
                                    ->exportPdf($this->filters)
                                    ->output();

                            },
                            'customer-report.pdf'
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
            'customer' => null,
        ];

        $this->form->fill($this->filters);

        $this->resetTable();
    }
}