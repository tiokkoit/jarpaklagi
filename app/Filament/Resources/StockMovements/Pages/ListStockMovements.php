<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Models\StockMovement;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use App\Filament\Resources\StockMovements\StockMovementResource;
use App\Filament\Resources\StockMovements\Widgets\TopActiveSkuChart;
use App\Filament\Resources\StockMovements\Widgets\StockMovementStats;
use App\Filament\Resources\StockMovements\Widgets\MovementReasonChart;


class ListStockMovements extends ListRecords
{
    protected static string $resource = StockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\Select::make('period_filter')
                        ->label('Periode')
                        ->options([
                            'today' => 'Hari Ini',
                            'this_week' => 'Minggu Ini',
                            'this_month' => 'Bulan Ini',
                            'this_year' => 'Tahun Ini',
                            'month' => 'Pilih Bulan/Tahun',
                            'year' => 'Pilih Tahun',
                            'range' => 'Rentang Tanggal',
                        ])
                        ->default('today')
                        ->reactive(),

                    Grid::make(2)
                        ->schema([
                            \Filament\Forms\Components\Select::make('month_number')
                                ->label('Bulan')
                                ->options([
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember'
                                ])
                                ->visible(fn($get) => $get('period_filter') === 'month')
                                ->default(now()->month)
                                ->required(),

                            \Filament\Forms\Components\Select::make('month_year')
                                ->label('Tahun')
                                ->options(array_combine(range(date('Y') - 5, date('Y')), range(date('Y') - 5, date('Y'))))
                                ->visible(fn($get) => $get('period_filter') === 'month')
                                ->default(now()->year)
                                ->required(),
                        ]),

                    \Filament\Forms\Components\Select::make('year_only')
                        ->label('Pilih Tahun')
                        ->options(array_combine(range(date('Y') - 5, date('Y')), range(date('Y') - 5, date('Y'))))
                        ->visible(fn($get) => $get('period_filter') === 'year')
                        ->default(now()->year)
                        ->required(),

                    Grid::make(2)
                        ->schema([
                            \Filament\Forms\Components\DatePicker::make('start_date')
                                ->label('Dari Tanggal')
                                ->visible(fn($get) => $get('period_filter') === 'range')
                                ->required(),
                            \Filament\Forms\Components\DatePicker::make('end_date')
                                ->label('Sampai Tanggal')
                                ->visible(fn($get) => $get('period_filter') === 'range')
                                ->required(),
                        ]),

                    \Filament\Forms\Components\Select::make('type')
                        ->label('Tipe')
                        ->options([
                            'all' => 'Semua Tipe',
                            'in' => 'Stock In',
                            'out' => 'Stock Out',
                        ])
                        ->default('all'),
                ])
                ->action(function (array $data) {
                    $period = $data['period_filter'];
                    $start = null;
                    $end = null;

                    switch ($period) {
                        case 'today':
                            $start = \Carbon\Carbon::today();
                            $end = \Carbon\Carbon::today();
                            break;
                        case 'this_week':
                            $start = \Carbon\Carbon::now()->startOfWeek();
                            $end = \Carbon\Carbon::now()->endOfWeek();
                            break;
                        case 'this_month':
                            $start = \Carbon\Carbon::now()->startOfMonth();
                            $end = \Carbon\Carbon::now()->endOfMonth();
                            break;
                        case 'this_year':
                            $start = \Carbon\Carbon::now()->startOfYear();
                            $end = \Carbon\Carbon::now()->endOfYear();
                            break;
                        case 'month':
                            $start = \Carbon\Carbon::create($data['month_year'], $data['month_number'], 1)->startOfMonth();
                            $end = \Carbon\Carbon::create($data['month_year'], $data['month_number'], 1)->endOfMonth();
                            break;
                        case 'year':
                            $start = \Carbon\Carbon::create($data['year_only'], 1, 1)->startOfYear();
                            $end = \Carbon\Carbon::create($data['year_only'], 1, 1)->endOfYear();
                            break;
                        case 'range':
                            $start = \Carbon\Carbon::parse($data['start_date']);
                            $end = \Carbon\Carbon::parse($data['end_date']);
                            break;
                    }

                    return redirect()->route('exports.stock-movements.pdf', [
                        'type' => $data['type'],
                        'start_date' => $start?->format('Y-m-d'),
                        'end_date' => $end?->format('Y-m-d'),
                    ]);
                }),
            \Filament\Actions\CreateAction::make()
                ->label('Tambah Stock Movement')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StockMovementStats::class,
            MovementReasonChart::class,
            TopActiveSkuChart::class,
        ];
    }


    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(StockMovement::count()),

            'in' => Tab::make('Stock In')
                ->query(fn($query) => $query->where('type', 'in'))
                ->badge(StockMovement::where('type', 'in')->count())
                ->badgeColor('success'),

            'out' => Tab::make('Stock Out')
                ->query(fn($query) => $query->where('type', 'out'))
                ->badge(StockMovement::where('type', 'out')->count())
                ->badgeColor('danger'),

            'today' => Tab::make('Today')
                ->query(fn($query) => $query->whereDate('created_at', today()))
                ->badge(StockMovement::whereDate('created_at', today())->count())
                ->badgeColor('info'),
        ];
    }
}