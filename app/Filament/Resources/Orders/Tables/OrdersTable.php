<?php

namespace App\Filament\Resources\Orders\Tables;

use Carbon\Carbon;
use App\Models\Order;
use Filament\Tables\Table;
use App\Models\SalesReport;
use Filament\Actions\Action;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\ActionGroup;
use Filament\Tables\Filters\Filter;
use App\Services\StockMovementService;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use App\Filament\Resources\Orders\OrderResource;
use Filament\Forms\Components\Select as FormSelect;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)

            ->columns([
                TextColumn::make('order_date')
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
                    ->description(fn (Order $record): string => $record->phone ?? '') 
                    ->searchable()
                    ->sortable()
                    ->wrap(),

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
                    ->wrap(),

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

            ->filters([
                // (Logika filter tetap sama sesuai kodingan kamu sebelumnya)
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
                        DatePicker::make('start')->label('Dari')->visible(fn($get) => $get('preset') === 'range'),
                        DatePicker::make('end')->label('Sampai')->visible(fn($get) => $get('preset') === 'range'),
                        FormSelect::make('month_year')->label('Tahun')->options(fn() => array_combine(range(date('Y') - 3, date('Y') + 1), range(date('Y') - 3, date('Y') + 1)))->visible(fn($get) => $get('preset') === 'month'),
                        FormSelect::make('month_number')->label('Bulan')->options([1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'])->visible(fn($get) => $get('preset') === 'month'),
                        FormSelect::make('year_only')->label('Pilih Tahun')->options(fn() => array_combine(range(date('Y') - 3, date('Y') + 1), range(date('Y') - 3, date('Y') + 1)))->visible(fn($get) => $get('preset') === 'year'),
                    ])
                    ->query(function ($query, array $data) {
                        $preset = $data['preset'] ?? null;
                        if ($preset === 'today') return $query->whereDate('order_date', Carbon::today());
                        if ($preset === 'last_3') return $query->whereBetween('order_date', [Carbon::now()->subDays(2), Carbon::now()]);
                        if ($preset === 'this_week') return $query->whereBetween('order_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                        if ($preset === 'last_week') return $query->whereBetween('order_date', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
                        if ($preset === 'month' && !empty($data['month_year']) && !empty($data['month_number'])) return $query->whereYear('order_date', $data['month_year'])->whereMonth('order_date', $data['month_number']);
                        if ($preset === 'year' && !empty($data['year_only'])) return $query->whereYear('order_date', $data['year_only']);
                        if ($preset === 'range' && !empty($data['start']) && !empty($data['end'])) return $query->whereBetween('order_date', [Carbon::parse($data['start']), Carbon::parse($data['end'])]);
                    }),
            ])

            ->actions([
                // --- ⚙️ GRUP AKSI DINAMIS ---
                ActionGroup::make([
                    ViewAction::make(),
                    
                    EditAction::make()
                        ->visible(fn (Order $record) => $record->status === 'NEW' || $record->status === 'DIKIRIM'),

                    // 🟢 AKSI: KIRIM (Hanya muncul jika NEW)
                    Action::make('validate_and_send_table')
                        ->label('Validate & Kirim')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->visible(fn (Order $record) => $record->status === 'NEW')
                        ->requiresConfirmation()
                        ->action(function (Order $record) {
                            $pkg = $record->productPackage;
                            if (!$pkg) {
                                Notification::make()->danger()->title('Paket tidak ditemukan.')->send();
                                return;
                            }

                            try {
                                StockMovementService::stockOut($pkg->product_id, 'order', ($pkg->pcs_per_package * $record->quantity), [
                                    'reference_type' => Order::class,
                                    'reference_id' => $record->id,
                                ]);

                                $record->update(['status' => 'DIKIRIM']);
                                Notification::make()->success()->title('Pesanan Dikirim')->body('Stok berkurang.')->send();
                            } catch (\Exception $e) {
                                $record->update(['status' => 'CANCEL']);
                                self::createSalesReportStatic($record, 'CANCEL');
                                Notification::make()->danger()->title('Stok Tidak Cukup')->body('Pesanan dibatalkan.')->send();
                            }
                        }),

                    // 🔴 AKSI: BATAL (Hanya muncul jika NEW)
                    Action::make('cancel_table')
                        ->label('Batalkan Pesanan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn (Order $record) => $record->status === 'NEW')
                        ->requiresConfirmation()
                        ->action(function (Order $record) {
                            $record->update(['status' => 'CANCEL']);
                            self::createSalesReportStatic($record, 'CANCEL');
                            Notification::make()->danger()->title('Pesanan Dibatalkan')->send();
                        }),

                    // 🔵 AKSI: SELESAI (Hanya muncul jika DIKIRIM)
                    Action::make('complete_table')
                        ->label('Selesai')
                        ->icon('heroicon-o-check-badge')
                        ->color('primary')
                        ->visible(fn (Order $record) => $record->status === 'DIKIRIM')
                        ->requiresConfirmation()
                        ->action(function (Order $record) {
                            $record->update(['status' => 'SELESAI']);
                            self::createSalesReportStatic($record, 'SELESAI');
                            Notification::make()->success()->title('Transaksi Pesanan Selesai')->send();
                        }),

                    // 🟡 AKSI: KEMBALIKAN (Hanya muncul jika DIKIRIM)
                    Action::make('return_table')
                        ->label('Kembalikan (Return)')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->color('warning')
                        ->visible(fn (Order $record) => $record->status === 'DIKIRIM')
                        ->requiresConfirmation()
                        ->action(function (Order $record) {
                            $pkg = $record->productPackage;
                            StockMovementService::stockIn($pkg->product_id, 'return_from_order', ($pkg->pcs_per_package * $record->quantity), [
                                'reference_type' => Order::class,
                                'reference_id' => $record->id,
                            ]);

                            $record->update(['status' => 'DIKEMBALIKAN']);
                            self::createSalesReportStatic($record, 'DIKEMBALIKAN');
                            Notification::make()->warning()->title('Pesanan Retur, Stok Direstorasi')->send();
                        }),
                ])->icon('heroicon-m-ellipsis-vertical')
            ]);
    }

    /**
     * Helper Static untuk mencatat ke Sales Report (agar sinkron dengan EditOrder)
     */
    protected static function createSalesReportStatic(Order $order, string $status): void
    {
        SalesReport::create([
            'report_date' => now()->toDateString(),
            'customer_name' => $order->customer_name,
            'customer_address' => $order->customer_address,
            'phone' => $order->phone,
            'kecamatan' => $order->kecamatan,
            'kota' => $order->kota,
            'province' => $order->province,
            'product_package_id' => $order->product_package_id,
            'quantity' => $order->quantity,
            'price' => $order->price,
            'total_price' => $order->total_price,
            'status' => $status,
            'payment' => $order->payment,
        ]);
    }
}