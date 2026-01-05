<?php

namespace App\Filament\Resources\SalesReports\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\Select as FormSelect;
use Filament\Forms\Components\DatePicker;
use Carbon\Carbon;
use App\Models\SalesReport;

class SalesReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // --- 🔒 LOCK INTERACTION ---
            // Karena laporan bersifat arsip, kita matikan klik baris agar aman.
            ->recordUrl(null)
            ->recordAction(null)

            ->columns([
                TextColumn::make('report_date')
                    ->label('Tanggal')
                    ->date('d/m/y')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'info' => 'NEW',
                        'gray' => 'DIKIRIM',
                        'danger' => 'CANCEL',
                        'success' => 'SELESAI',
                        'warning' => 'DIKEMBALIKAN',
                    ])
                    ->icons([
                        'heroicon-o-sparkles' => 'NEW',
                        'heroicon-o-truck' => 'DIKIRIM',
                        'heroicon-o-no-symbol' => 'CANCEL',
                        'heroicon-o-check-badge' => 'SELESAI',
                        'heroicon-o-backspace' => 'DIKEMBALIKAN',
                    ])
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->icon('heroicon-o-user')
                    ->description(fn (SalesReport $record): string => $record->phone ?? '') 
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                // --- 📦 OPTIMASI RUANG: Alamat Digabung ---
                TextColumn::make('customer_address')
                    ->label('Detail Lokasi')
                    ->icon('heroicon-o-map-pin')
                    ->wrap()
                    ->description(fn (SalesReport $record): string => 
                        ($record->kecamatan ? "Kec. {$record->kecamatan}, " : "") . 
                        ($record->kota ? "{$record->kota}, " : "") . 
                        ($record->province ?? "")
                    )
                    ->searchable(['customer_address', 'kecamatan', 'kota', 'province']), 

                TextColumn::make('productPackage.name')
                    ->label('Paket Produk')
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->alignCenter()
                    ->weight('bold'),

                // Menambahkan Total Harga agar laporan keuangan lengkap
                TextColumn::make('total_price')
                    ->label('Nilai Transaksi')
                    ->money('idr')
                    ->sortable()
                    ->weight('black'),

                BadgeColumn::make('payment')
                    ->label('Metode')
                    ->colors([
                        'teal' => 'COD',
                        'purple' => 'TRANSFER',
                    ])
                    ->icons([
                        'heroicon-o-banknotes' => 'COD',
                        'heroicon-o-credit-card' => 'TRANSFER',
                    ]),
            ])

            // --- 🔵 STYLING & BEHAVIOR ---
            ->striped()
            ->defaultSort('report_date', 'desc')
            ->poll('60s') // Refresh otomatis untuk monitoring performa tim
            ->emptyStateHeading('Belum Ada Laporan Penjualan')
            ->emptyStateIcon('heroicon-o-document-chart-bar')

            ->filters([
                Filter::make('time')
                    ->label('Periode Laporan')
                    ->form([
                        FormSelect::make('preset')
                            ->label('Cepat Pilih Periode')
                            ->options([
                                '' => '-- Semua Waktu --',
                                'today' => 'Hari Ini',
                                'last_3' => '3 Hari Terakhir',
                                'this_week' => 'Minggu Ini',
                                'last_week' => 'Minggu Lalu',
                                'month' => 'Analisis Bulan',
                                'year' => 'Analisis Tahun',
                                'range' => 'Rentang Kustom',
                            ])
                            ->reactive(),

                        DatePicker::make('start')->label('Dari')->visible(fn($get) => $get('preset') === 'range'),
                        DatePicker::make('end')->label('Sampai')->visible(fn($get) => $get('preset') === 'range'),
                        
                        FormSelect::make('month_year')
                            ->label('Tahun')
                            ->options(fn() => array_combine(range(date('Y') - 3, date('Y') + 1), range(date('Y') - 3, date('Y') + 1)))
                            ->visible(fn($get) => $get('preset') === 'month'),

                        FormSelect::make('month_number')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ])
                            ->visible(fn($get) => $get('preset') === 'month'),

                        FormSelect::make('year_only')
                            ->label('Pilih Tahun')
                            ->options(fn() => array_combine(range(date('Y') - 3, date('Y') + 1), range(date('Y') - 3, date('Y') + 1)))
                            ->visible(fn($get) => $get('preset') === 'year'),
                    ])
                    ->query(function ($query, array $data) {
                        $preset = $data['preset'] ?? null;
                        if ($preset === 'today') return $query->whereDate('report_date', Carbon::today());
                        if ($preset === 'last_3') return $query->whereBetween('report_date', [Carbon::now()->subDays(2), Carbon::now()]);
                        if ($preset === 'this_week') return $query->whereBetween('report_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        if ($preset === 'last_week') return $query->whereBetween('report_date', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                        
                        if ($preset === 'month' && !empty($data['month_year']) && !empty($data['month_number'])) {
                            return $query->whereYear('report_date', $data['month_year'])->whereMonth('report_date', $data['month_number']);
                        }

                        if ($preset === 'year' && !empty($data['year_only'])) {
                            return $query->whereYear('report_date', $data['year_only']);
                        }

                        if ($preset === 'range' && !empty($data['start']) && !empty($data['end'])) {
                            return $query->whereBetween('report_date', [Carbon::parse($data['start']), Carbon::parse($data['end'])]);
                        }
                    }),
            ]);
    }
}