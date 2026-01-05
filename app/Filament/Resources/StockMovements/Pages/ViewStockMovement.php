<?php

namespace App\Filament\Resources\StockMovements\Pages;

use App\Filament\Resources\StockMovements\StockMovementResource;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class ViewStockMovement extends ViewRecord
{
    protected static string $resource = StockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            // Root Grid 3 Kolom
            Grid::make(['lg' => 3])
            ->schema([

                // --- 🟢 KOLOM KIRI: DATA UTAMA (2/3) ---
                Group::make([
                    Section::make('Informasi Pergerakan Stok')
                        ->icon('heroicon-m-arrows-right-left')
                        ->inlineLabel() // Label ke samping agar hemat tempat
                        ->schema([
                            Placeholder::make('product_name')
                                ->label('Nama Produk')
                                ->content(fn ($record) => $record->product?->name)
                                ->extraAttributes(['class' => 'font-bold text-primary-600 text-lg']),

                            Placeholder::make('type')
                                ->label('Tipe Pergerakan')
                                ->content(fn ($record) => $record->type === 'in' 
                                    ? new HtmlString('<span class="px-2 py-1 text-xs font-black rounded bg-success-50 text-success-700 ring-1 ring-inset ring-success-600/20">🟢 MASUK (IN)</span>')
                                    : new HtmlString('<span class="px-2 py-1 text-xs font-black rounded bg-danger-50 text-danger-700 ring-1 ring-inset ring-danger-600/20">🔴 KELUAR (OUT)</span>')
                                ),
                        ]),

                    Section::make('Rekapitulasi Mutasi Stok')
                        ->description('Perhitungan mutasi stok secara matematis.')
                        ->icon('heroicon-m-calculator')
                        ->schema([
                            Grid::make(3)->schema([
                                Placeholder::make('stock_before')
                                    ->label('Stok Awal')
                                    ->content(fn ($record) => number_format($record->stock_before) . ' Unit')
                                    ->extraAttributes(['class' => 'text-gray-500']),

                                Placeholder::make('mutasi')
                                    ->label('Mutasi')
                                    ->content(fn ($record) => $record->formatted_quantity . ' Unit')
                                    ->extraAttributes(fn ($record) => [
                                        'class' => 'font-bold ' . ($record->type === 'in' ? 'text-success-600' : 'text-danger-600')
                                    ]),

                                Placeholder::make('stock_after')
                                    ->label('Stok Akhir')
                                    ->content(fn ($record) => number_format($record->stock_after) . ' Unit')
                                    ->extraAttributes([
                                        'class' => 'text-3xl font-black text-primary-700 bg-primary-50 px-3 py-2 rounded-lg border-b-4 border-primary-500'
                                    ]),
                            ]),
                        ]),
                        Section::make()
                        ->compact()
                        ->schema([
                            Placeholder::make('id_log')
                                ->hiddenLabel()
                                ->content(fn ($record) => new HtmlString('<div class="text-[10px] text-gray-400 font-mono text-center">Log ID: #' . $record->id . ' | StockkuApp Audit System</div>')),
                        ]),
                ])->columnSpan(['lg' => 2]),
                

                // --- 🔵 KOLOM KANAN: SIDEBAR KONTEKS (1/3) ---
                Group::make([
                    Section::make('Detail Pencatatan')
                        ->icon('heroicon-m-shield-check')
                        ->schema([
                            Placeholder::make('created_at')
                                ->label('Waktu Pencatatan')
                                ->content(fn ($record) => $record->created_at?->format('d M Y, H:i')),

                            Placeholder::make('createdBy.name')
                                ->label('Dilakukan Oleh')
                                ->content(fn ($record) => $record->createdBy?->name ?? 'System'),
                            
                            Placeholder::make('reason_text')
                                ->label('Alasan Pergerakan Stok')
                                ->content(fn ($record) => $record->reason_text),
                        ]),

                    Section::make('Referensi & Catatan')
                        ->icon('heroicon-m-document-magnifying-glass')
                        ->schema([
                            Placeholder::make('reference')
                                ->label('Sumber Referensi')
                                ->content(function ($record) {
                                    if (!$record->reference) return 'Input Manual';
                                    return $record->reference_type === 'App\Models\Order' 
                                        ? "Order #{$record->reference->order_number}" 
                                        : 'Otomatis Sistem';
                                }),

                            Placeholder::make('notes')
                                ->label('Catatan Tambahan')
                                ->content(fn ($record) => $record->notes ?? '-')
                                ->extraAttributes(['class' => 'text-sm italic text-gray-600']),
                        ]),
                ])->columnSpan(['lg' => 1]),

            ])->columnSpanFull(),
        ]);
    }
}