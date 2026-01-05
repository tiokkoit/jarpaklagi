<?php

namespace App\Filament\Resources\Orders\Tables;

use Carbon\Carbon;
use App\Models\Order;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Forms\Components\Select as FormSelect;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // --- 🟢 LOGIKA NAVIGASI DINAMIS (TETAP) ---
            ->recordUrl(function (Order $record) {
                if (in_array($record->status, ['CANCEL', 'SELESAI', 'DIKEMBALIKAN'])) {
                    return OrderResource::getUrl('view', ['record' => $record]);
                }
                return OrderResource::getUrl('edit', ['record' => $record]);
            })

            ->columns([
                TextColumn::make('order_date')
                    ->label('Tanggal')
                    ->date('d/m/y') // Diperpendek formatnya agar hemat ruang
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
                    ->description(fn (Order $record): string => $record->phone ?? '') 
                    ->searchable()
                    ->sortable()
                    ->wrap(), // Membungkus nama panjang agar tidak memanjang ke samping

                // --- 📦 OPTIMASI ALAMAT LENGKAP (HIDDEN BY DEFAULT) ---
                TextColumn::make('customer_address')
                    ->label('Alamat Pengiriman')
                    ->icon('heroicon-o-map-pin')
                    ->wrap()
                    ->description(fn (Order $record): string => 
                        ($record->kecamatan ? "Kec. {$record->kecamatan}, " : "") . 
                        ($record->kota ? "{$record->kota}, " : "") . 
                        ($record->province ?? "")
                    )
                    ->searchable(['customer_address', 'kecamatan', 'kota', 'province']), 

                TextColumn::make('productPackage.name')
                    ->label('Paket')
                    ->weight('bold')
                    ->wrap(), // Membungkus nama paket jika panjang

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->alignCenter()
                    ->weight('bold'),

                TextColumn::make('total_price')
                    ->label('Total')
                    ->money('idr')
                    ->sortable()
                    ->weight('bold'),

                BadgeColumn::make('payment')
                    ->label('Metode')
                    ->colors([
                        'teal' => 'COD',
                        'purple' => 'TRANSFER',
                    ])
                    ->icons([
                        'heroicon-o-banknotes' => 'COD',
                        'heroicon-o-credit-card' => 'TRANSFER',
                    ])
            ])

            ->striped()
            ->defaultSort('order_date', 'desc')
            ->poll('60s')
            ->emptyStateHeading('Belum Ada Pesanan')
            ->emptyStateIcon('heroicon-o-shopping-cart')

            ->filters([
                Filter::make('time')
                    ->label('Periode Waktu')
                    ->form([
                        FormSelect::make('preset')
                            ->label('Cepat Pilih')
                            ->options([
                                '' => '-- Semua Waktu --',
                                'today' => 'Hari Ini',
                                'last_3' => '3 Hari Terakhir',
                                'this_week' => 'Minggu Ini',
                                'last_week' => 'Minggu Lalu',
                                'month' => 'Pilih Bulan',
                                'year' => 'Pilih Tahun',
                                'range' => 'Rentang Kustom',
                            ])
                            ->reactive(),

                        DatePicker::make('start')
                            ->label('Dari Tanggal')
                            ->visible(fn($get) => $get('preset') === 'range'),

                        DatePicker::make('end')
                            ->label('Sampai Tanggal')
                            ->visible(fn($get) => $get('preset') === 'range'),

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
                        if ($preset === 'today') return $query->whereDate('order_date', Carbon::today());
                        if ($preset === 'last_3') return $query->whereBetween('order_date', [Carbon::now()->subDays(2), Carbon::now()]);
                        if ($preset === 'this_week') return $query->whereBetween('order_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        if ($preset === 'last_week') return $query->whereBetween('order_date', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                        
                        if ($preset === 'month' && !empty($data['month_year']) && !empty($data['month_number'])) {
                            return $query->whereYear('order_date', $data['month_year'])->whereMonth('order_date', $data['month_number']);
                        }

                        if ($preset === 'year' && !empty($data['year_only'])) {
                            return $query->whereYear('order_date', $data['year_only']);
                        }

                        if ($preset === 'range' && !empty($data['start']) && !empty($data['end'])) {
                            return $query->whereBetween('order_date', [Carbon::parse($data['start']), Carbon::parse($data['end'])]);
                        }
                    }),
            ])
            ->actions([
                ViewAction::make()->iconButton(),
                EditAction::make()
                    ->iconButton()
                    ->visible(fn (Order $record) => !in_array($record->status, ['CANCEL', 'SELESAI', 'DIKEMBALIKAN'])),
            ]);
    }
}