<?php

namespace App\Filament\Resources\SalesReports\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select as FormSelect;
use Filament\Forms\Components\DatePicker;
use Carbon\Carbon;

class SalesReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('report_date')->label('Tanggal')->date(),
            BadgeColumn::make('status')
                ->label('Status')
                ->colors([
                    'info' => fn($state): bool => $state === 'NEW',
                    'gray' => fn($state): bool => $state === 'DIKIRIM',
                    'danger' => fn($state): bool => $state === 'CANCEL',
                    'success' => fn($state): bool => $state === 'SELESAI',
                    'warning' => fn($state): bool => $state === 'DIKEMBALIKAN',
                ])
                ->icons([
                    'heroicon-o-sparkles' => 'NEW',
                    'heroicon-o-truck' => 'DIKIRIM',
                    'heroicon-o-no-symbol' => 'CANCEL',
                    'heroicon-o-check-badge' => 'SELESAI',
                    'heroicon-o-backspace' => 'DIKEMBALIKAN',
                ]),
            TextColumn::make('customer_name')->label('Nama Customer')->icon('heroicon-o-user')->searchable(),
            TextColumn::make('phone')->label('No HP')->icon('heroicon-o-phone')->searchable(),
            TextColumn::make('customer_address')->label('Alamat Customer')->icon('heroicon-o-map-pin')->searchable(),
            TextColumn::make('kecamatan')->label('Kecamatan')->searchable(),
            TextColumn::make('kota')->label('Kota')->searchable(),
            TextColumn::make('province')->label('Provinsi')->searchable(),
            TextColumn::make('productPackage.name')->label('Paket'),
            TextColumn::make('quantity')->label('Jumlah Paket'),
            BadgeColumn::make('payment')->label('Payment')
                ->colors([
                    'teal' => fn($state): bool => $state === 'COD',
                    'purple' => fn($state): bool => $state === 'TRANSFER',
                ])
                ->icons([
                    'heroicon-o-banknotes' => 'COD',
                    'heroicon-o-credit-card' => 'TRANSFER',
                ]),
        ])
            ->filters([
                Filter::make('time')
                    ->label('Time')
                    ->form([
                        FormSelect::make('preset')
                            ->label('Filter')
                            ->options([
                                'today' => 'Today',
                                'last_3' => 'Last 3 Days',
                                'this_week' => 'This Week',
                                'last_week' => 'Last Week',
                                'month' => 'Select Month',
                                'year' => 'Select Year',
                                'range' => 'Custom Range',
                            ])
                            ->reactive(),

                        DatePicker::make('start')
                            ->label('Start Date')
                            ->visible(fn($get) => $get('preset') === 'range'),

                        DatePicker::make('end')
                            ->label('End Date')
                            ->visible(fn($get) => $get('preset') === 'range'),

                        FormSelect::make('month_year')
                            ->label('Year')
                            ->options(fn() => array_combine(range(date('Y') - 5, date('Y') + 1), range(date('Y') - 5, date('Y') + 1)))
                            ->visible(fn($get) => $get('preset') === 'month'),

                        FormSelect::make('month_number')
                            ->label('Month')
                            ->options(fn() => array_combine(range(1, 12), ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']))
                            ->visible(fn($get) => $get('preset') === 'month'),

                        FormSelect::make('year_only')
                            ->label('Year')
                            ->options(fn() => array_combine(range(date('Y') - 5, date('Y') + 1), range(date('Y') - 5, date('Y') + 1)))
                            ->visible(fn($get) => $get('preset') === 'year'),
                    ])
                    ->query(function ($query, array $data) {
                        $preset = $data['preset'] ?? null;
                        if ($preset === 'today') {
                            return $query->whereDate('report_date', Carbon::today()->toDateString());
                        }

                        if ($preset === 'last_3') {
                            return $query->whereBetween('report_date', [Carbon::now()->subDays(2)->toDateString(), Carbon::now()->toDateString()]);
                        }

                        if ($preset === 'this_week') {
                            return $query->whereBetween('report_date', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()]);
                        }

                        if ($preset === 'last_week') {
                            return $query->whereBetween('report_date', [Carbon::now()->subWeek()->startOfWeek()->toDateString(), Carbon::now()->subWeek()->endOfWeek()->toDateString()]);
                        }

                        if ($preset === 'month' && !empty($data['month_year']) && !empty($data['month_number'])) {
                            $y = (int) $data['month_year'];
                            $m = (int) $data['month_number'];
                            return $query->whereYear('report_date', $y)->whereMonth('report_date', $m);
                        }

                        if ($preset === 'year' && !empty($data['year_only'])) {
                            $y = (int) $data['year_only'];
                            return $query->whereYear('report_date', $y);
                        }

                        if ($preset === 'range' && !empty($data['start']) && !empty($data['end'])) {
                            return $query->whereBetween('report_date', [Carbon::parse($data['start'])->toDateString(), Carbon::parse($data['end'])->toDateString()]);
                        }

                        return $query;
                    }),
            ])
            ->defaultSort('report_date', 'desc');
    }

}
