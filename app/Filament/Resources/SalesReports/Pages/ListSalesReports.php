<?php

namespace App\Filament\Resources\SalesReports\Pages;

use Carbon\Carbon;
use App\Models\SalesReport;
use Filament\Actions\Action;
use App\Models\ProductPackage;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SalesReports\SalesReportResource;
use App\Filament\Resources\SalesReports\Widgets\OrderStatusDonut;
use App\Filament\Resources\SalesReports\Widgets\SalesRevenueChart;
use App\Filament\Resources\SalesReports\Widgets\PaymentMethodChart;
use App\Filament\Resources\SalesReports\Widgets\PaymentMethodDonut;
use App\Filament\Resources\SalesReports\Widgets\RevenueAnalyticsChart;
use App\Filament\Resources\SalesReports\Widgets\RevenueProfitTrendChart;
use App\Filament\Resources\SalesReports\Widgets\SalesReportStatsOverview;
use App\Filament\Resources\SalesReports\Widgets\ProvinceDistributionChart;
use App\Filament\Resources\SalesReports\Widgets\GeographicDistributionChart;

class ListSalesReports extends ListRecords
{
    protected static string $resource = SalesReportResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            SalesReportStatsOverview::class,
            RevenueProfitTrendChart::class,
            PaymentMethodDonut::class,
            OrderStatusDonut::class,
            ProvinceDistributionChart::class, // New
            GeographicDistributionChart::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->form([
                    Select::make('period_filter')
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
                            Select::make('month_number')
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

                            Select::make('month_year')
                                ->label('Tahun')
                                ->options(array_combine(range(date('Y') - 5, date('Y')), range(date('Y') - 5, date('Y'))))
                                ->visible(fn($get) => $get('period_filter') === 'month')
                                ->default(now()->year)
                                ->required(),
                        ]),

                    Select::make('year_only')
                        ->label('Pilih Tahun')
                        ->options(array_combine(range(date('Y') - 5, date('Y')), range(date('Y') - 5, date('Y'))))
                        ->visible(fn($get) => $get('period_filter') === 'year')
                        ->default(now()->year)
                        ->required(),

                    Grid::make(2)
                        ->schema([
                            DatePicker::make('start_date')
                                ->label('Dari Tanggal')
                                ->visible(fn($get) => $get('period_filter') === 'range')
                                ->required(),
                            DatePicker::make('end_date')
                                ->label('Sampai Tanggal')
                                ->visible(fn($get) => $get('period_filter') === 'range')
                                ->required(),
                        ]),

                    Select::make('status')
                        ->label('Status')
                        ->options([
                            'all' => 'Semua Status',
                            'CANCEL' => 'Cancel',
                            'DIKEMBALIKAN' => 'Dikembalikan',
                            'SELESAI' => 'Selesai',
                        ])
                        ->default('all'),
                ])
                ->action(function (array $data) {
                    $period = $data['period_filter'];
                    $start = null;
                    $end = null;

                    switch ($period) {
                        case 'today':
                            $start = Carbon::today();
                            $end = Carbon::today();
                            break;
                        case 'this_week':
                            $start = Carbon::now()->startOfWeek();
                            $end = Carbon::now()->endOfWeek();
                            break;
                        case 'this_month':
                            $start = Carbon::now()->startOfMonth();
                            $end = Carbon::now()->endOfMonth();
                            break;
                        case 'this_year':
                            $start = Carbon::now()->startOfYear();
                            $end = Carbon::now()->endOfYear();
                            break;
                        case 'month':
                            $start = Carbon::create($data['month_year'], $data['month_number'], 1)->startOfMonth();
                            $end = Carbon::create($data['month_year'], $data['month_number'], 1)->endOfMonth();
                            break;
                        case 'year':
                            $start = Carbon::create($data['year_only'], 1, 1)->startOfYear();
                            $end = Carbon::create($data['year_only'], 1, 1)->endOfYear();
                            break;
                        case 'range':
                            $start = Carbon::parse($data['start_date']);
                            $end = Carbon::parse($data['end_date']);
                            break;
                    }

                    return redirect()->route('exports.sales-report.pdf', [
                        'status' => $data['status'],
                        'start_date' => $start?->format('Y-m-d'),
                        'end_date' => $end?->format('Y-m-d'),
                    ]);
                }),

            Action::make('import_csv')
                ->label('Import CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->form([
                    FileUpload::make('file')
                        ->label('CSV File')
                        ->required()
                        ->disk('local')
                        ->directory('imports'),
                ])
                ->action(fn(array $data) => $this->handleImport($data)),
        ];
    }

    /**
     * Logika TAB & Badge Reaktif
     */
    public function getTabs(): array
    {
        // 1. Ambil data filter 'time' langsung dari state Livewire
        $timeData = $this->tableFilters['time'] ?? [];

        // 2. Siapkan base query dengan filter waktu yang aktif
        $baseQuery = SalesReport::query();
        $baseQuery = $this->applyTimeFilter($baseQuery, $timeData);

        return [
            'all' => Tab::make('ALL')
                ->badge((clone $baseQuery)->count())
                ->badgeColor('secondary'),

            'cancel' => Tab::make('CANCEL')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'CANCEL'))
                ->badge((clone $baseQuery)->where('status', 'CANCEL')->count())
                ->badgeColor('danger')
                ->icon('heroicon-o-no-symbol'),

            'selesai' => Tab::make('SELESAI')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'SELESAI'))
                ->badge((clone $baseQuery)->where('status', 'SELESAI')->count())
                ->badgeColor('success')
                ->icon('heroicon-o-check-badge'),

            'dikembalikan' => Tab::make('DIKEMBALIKAN')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'DIKEMBALIKAN'))
                ->badge((clone $baseQuery)->where('status', 'DIKEMBALIKAN')->count())
                ->badgeColor('warning')
                ->icon('heroicon-o-backspace'),
        ];
    }

    /**
     * Helper untuk sinkronisasi logic filter waktu di Query dan Badge
     */
    protected function applyTimeFilter(Builder $query, array $data): Builder
    {
        $preset = $data['preset'] ?? null;

        return match ($preset) {
            'today' => $query->whereDate('report_date', Carbon::today()->toDateString()),
            'last_3' => $query->whereBetween('report_date', [Carbon::now()->subDays(2)->toDateString(), Carbon::now()->toDateString()]),
            'this_week' => $query->whereBetween('report_date', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()]),
            'last_week' => $query->whereBetween('report_date', [Carbon::now()->subWeek()->startOfWeek()->toDateString(), Carbon::now()->subWeek()->endOfWeek()->toDateString()]),
            'month' => (isset($data['month_year'], $data['month_number']))
            ? $query->whereYear('report_date', (int) $data['month_year'])->whereMonth('report_date', (int) $data['month_number'])
            : $query,
            'year' => (!empty($data['year_only'])) ? $query->whereYear('report_date', (int) $data['year_only']) : $query,
            'range' => (isset($data['start'], $data['end']))
            ? $query->whereBetween('report_date', [Carbon::parse($data['start'])->toDateString(), Carbon::parse($data['end'])->toDateString()])
            : $query,
            default => $query,
        };
    }

    /**
     * Logika Import CSV
     */
    protected function handleImport(array $data): void
    {
        $path = $data['file'] ?? null;
        if (empty($path))
            return;

        $fullPath = Storage::disk('local')->path($path);
        if (!file_exists($fullPath)) {
            Notification::make()->danger()->title('File tidak ditemukan')->send();
            return;
        }

        if (($handle = fopen($fullPath, 'r')) !== false) {
            $header = null;
            $count = 0;
            $skipped = 0;
            $skipDetails = [];
            $rowIndex = 0;

            while (($row = fgetcsv($handle, 0, ',')) !== false) {
                $rowIndex++;
                if (!$header) {
                    $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', $row[0]);
                    $header = array_map(fn($h) => strtolower(trim($h)), $row);
                    continue;
                }

                $dataRow = array_combine($header, $row);

                // Validasi kolom minimal
                if (empty($dataRow['report_date']) || empty($dataRow['quantity']) || empty($dataRow['status'])) {
                    $skipped++;
                    $skipDetails[] = "Line {$rowIndex}: Missing required columns.";
                    continue;
                }

                // Resolving package
                $pkg = ProductPackage::where('id', $dataRow['product_package_id'] ?? null)
                    ->orWhere('code', $dataRow['product_package_code'] ?? null)
                    ->orWhere('name', $dataRow['product_package_name'] ?? null)
                    ->first();

                if (!$pkg) {
                    $skipped++;
                    $skipDetails[] = "Line {$rowIndex}: Product package not found.";
                    continue;
                }

                // Status mapping
                $rawStatus = strtolower(trim($dataRow['status']));
                $statusMap = [
                    'cancel' => 'CANCEL',
                    'selesai' => 'SELESAI',
                    'dikembalikan' => 'DIKEMBALIKAN',
                ];

                if (!isset($statusMap[$rawStatus])) {
                    $skipped++;
                    $skipDetails[] = "Line {$rowIndex}: Invalid status '{$rawStatus}'.";
                    continue;
                }

                SalesReport::create([
                    'report_date' => $dataRow['report_date'],
                    'customer_name' => $dataRow['customer_name'] ?? null,
                    'customer_address' => $dataRow['customer_address'] ?? null,
                    'phone' => $dataRow['phone'] ?? null,
                    'kecamatan' => $dataRow['kecamatan'] ?? null,
                    'kota' => $dataRow['kota'] ?? null,
                    'province' => $dataRow['province'] ?? null,
                    'product_package_id' => $pkg->id,
                    'quantity' => (int) $dataRow['quantity'],
                    'price' => $pkg->price,
                    'total_price' => $pkg->price * (int) $dataRow['quantity'],
                    'status' => $statusMap[$rawStatus],
                    'payment' => $dataRow['payment'] ?? null,
                ]);
                $count++;
            }
            fclose($handle);

            if ($skipped > 0) {
                $logFile = 'import_logs/sales_' . now()->format('Ymd_His') . '.log';
                Storage::disk('local')->put($logFile, implode("\n", $skipDetails));
                Notification::make()->warning()->title("Import selesai dengan {$skipped} baris dilewati.")->body("Log: storage/app/{$logFile}")->send();
            } else {
                Notification::make()->success()->title("Berhasil mengimpor {$count} data.")->send();
            }
        }
    }
}